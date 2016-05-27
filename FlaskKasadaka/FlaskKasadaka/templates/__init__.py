from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash
from sparqlInterface import executeSparqlQuery, executeSparqlUpdate
from datetime import datetime
import config
import glob
import re
import urllib

app = Flask(__name__)
@app.route('/')
def index():
    """ Index page
	Only used to confirm hosting is working correctly
	"""
    return 'This is the Kasadaka Vxml generator'


@app.route('/main.vxml')
def main():
	#if a language has been chosen, go to the main menu.
	#the main menu contains all available functions, and redirects to them.
    if 'lang' in request.args:
        lang = config.LanguageVars(request.args)
        #list of options in initial menu: link to file, and audio description of the choice
        options = [
                ['lookupFertilizer.vxml?lang='+lang.language,    lang.audioInterfaceURL+'lookupFertilizer.wav']
                ]


        return render_template(
        'main.vxml',
        interfaceAudioDir = lang.audioInterfaceURL,
        welcomeAudio = 'welcome.wav',
        questionAudio = "mainMenuQuestion.wav",
        options = options)
    else:
        #give your language
        languagesQuery = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
        PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
        PREFIX lexvo: <http://lexvo.org/ontology#>

        SELECT DISTINCT  ?voicelabel  WHERE {
              ?voicelabel   rdfs:subPropertyOf speakle:voicelabel.

        }"""
        languages = executeSparqlQuery(languagesQuery)
        for language in languages:
            language.append(config.audioURLbase + language[0].rsplit('_', 1)[-1] + "/interface/" + language[0].rsplit('/', 1)[-1] + ".wav")
            language.append(language[0].rsplit('_', 1)[-1])
            language[0] = "main.vxml?lang=" + language[0].rsplit('_', 1)[-1]


        return render_template(
        'language.vxml',
        options = languages,
        audioDir = config.audioURLbase,
        questionAudio = config.audioURLbase+config.defaultLanguage+"/interface/chooseLanguage.wav"

        )



@app.route('/lookupFertilizer.vxml')
def lookupFertilizer():
    #process the language
    lang = config.LanguageVars(request.args)

    #if the chosen product has been entered, show results
    if 'product' in request.args:
        choice = request.args['product']

        query = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
        PREFIX fa: <http://example.org/fertapp/>

        SELECT DISTINCT ?voicelabel_en  WHERE {
            <"""+choice+ """> fa:has_fertilizer ?fert.
            ?fert speakle:voicelabel_en ?voicelabel_en
            }
        """
        query = lang.replaceVoicelabels(query)

        results = executeSparqlQuery(query)

        return render_template(
            'result.vxml',
            interfaceAudioDir = lang.audioInterfaceURL,
            messageAudio = 'presentFertilizer.wav',
            redirect = 'main.vxml?lang='+lang.language,
            results = results)


    #if no choice was made, offer choices of products to get offerings from
    query = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
    PREFIX fa: <http://example.org/fertapp/>

    SELECT DISTINCT ?product ?voicelabel_en  WHERE {
    ?product rdf:type fa:crop.
    ?product speakle:voicelabel_en ?voicelabel_en
    }"""
    query = lang.replaceVoicelabels(query)
    choices = executeSparqlQuery(query)
    #add the url of this page to the links, so the user gets the results
    #also keep the language
    for choice in choices:
        choice[0] = 'lookupFertilizer.vxml?lang='+lang.language+'&amp;product=' + choice[0]

    return render_template(
    'menu.vxml',
    options = choices,
    interfaceAudioDir = lang.audioInterfaceURL,
    questionAudio = "chooseYourProduct.wav"
    )


@app.route('/audioreferences.html')
def audioReferences():
    finalResultsInterface = []
    finalResultsSparql = []
    pythonFiles = glob.glob(config.pythonFilesDir+'*.py')
    pythonFiles.extend(glob.glob(config.pythonFilesDir+'templates/*'))
    resultsInterface = []
    wavFilePattern = re.compile("""([^\s\\/+"']+\.wav)""",re.I)
    for pythonFile in pythonFiles:
        text = open(pythonFile).read()
        for match in wavFilePattern.findall(text):
            #ignore match on regex above
            if match != "\.wav":
                resultsInterface.append(match)
    #remove duplicates
    resultsInterface.extend(['1.wav','2.wav','3.wav','4.wav','5.wav','6.wav','7.wav','8.wav','9.wav','0.wav','hash.wav','star.wav'])


    languages = []
    getLanguagesQuery = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
    PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
    PREFIX lexvo: <http://lexvo.org/ontology#>

    SELECT DISTINCT  ?voicelabel  WHERE {
          ?voicelabel   rdfs:subPropertyOf speakle:voicelabel.



    }"""
    outputGetLanguagesQuery = executeSparqlQuery(getLanguagesQuery)
    #get the language code behind the last slash
    for string in outputGetLanguagesQuery:
        #also add the language itself to choose language
        resultsInterface.append(string[0].rsplit('/', 1)[-1]+".wav")
        #add the langauges
        languages.append(string[0].rsplit('_', 1)[-1])

    usedWaveFiles = set(resultsInterface)
    for lang in languages:
        nonExistingWaveFiles = []
        existingWaveFiles = []
        for waveFile in usedWaveFiles:

            url = config.audioURLbase +"/"+lang+"/interface/"+ waveFile
            if urllib.urlopen(url).getcode() == 200:
                existingWaveFiles.append(waveFile)
            else:
                nonExistingWaveFiles.append(waveFile)
                existingWaveFiles = sorted(existingWaveFiles)
                nonExistingWaveFiles = sorted(nonExistingWaveFiles)
        finalResultsInterface.append([lang,existingWaveFiles,nonExistingWaveFiles])

    #check the DB for subjects without a voicelabel
    noVoicelabelQuery = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
    PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT DISTINCT ?subject   WHERE {
    ?subject rdf:type	rdfs:Resource .
    FILTER(NOT EXISTS {?subject speakle:voicelabel_en ?voicelabel_en .})
    }"""
    subjectsWithoutVoicelabel = executeSparqlQuery(noVoicelabelQuery)
    subjectsWithoutVoicelabel = sorted(subjectsWithoutVoicelabel)
            #TODO: implement language


    #check the DB for subjects with a voicelabel, to check whether it exists or not
    voicelabelQuery = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
    PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT DISTINCT ?subject ?voicelabel_en  WHERE {
    ?subject rdf:type	rdfs:Resource .
    ?subject speakle:voicelabel_en ?voicelabel_en .
    }"""




    for lang in languages:


        voicelabelQuery = voicelabelQuery.replace("voicelabel_en","voicelabel_"+lang)
        subjectsWithVoicelabel = executeSparqlQuery(voicelabelQuery)
        sparqlNonExistingWaveFiles = []
        sparqlExistingWaveFiles = []
        for subject in subjectsWithVoicelabel:

            url = subject[1]
            if urllib.urlopen(url).getcode() == 200:
                sparqlExistingWaveFiles.append(subject[1])
            else:
                sparqlNonExistingWaveFiles.append(subject[1])
                sparqlExistingWaveFiles = sorted(sparqlExistingWaveFiles)
                sparqlNonExistingWaveFiles = sorted(sparqlNonExistingWaveFiles)
        finalResultsSparql.append([lang,sparqlExistingWaveFiles,sparqlNonExistingWaveFiles])



    return render_template(
    'audiofiles.html',
    scannedFiles = pythonFiles,
    interfaceResults = finalResultsInterface,
    subjectsWithoutVoicelabel = subjectsWithoutVoicelabel,
    sparqlResults = finalResultsSparql)


if __name__ == '__main__':
    app.run(host="0.0.0.0",debug=config.debug)
