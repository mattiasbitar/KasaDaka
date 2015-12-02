
from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash

from getSparql import executeSparqlQuery

import config
import callhandler

app = Flask(__name__)
@app.route('/')
def index():
    return 'This is the Kasadaka Vxml generator'

@app.route('/main.vxml')
def main():
    if 'lang' in request.args:
        lang = config.LanguageVars(request.args)
        #list of options in initial menu: link to file, and audio description of the choice
        options = [
                ['requestProductOfferings.vxml?lang='+lang,    audioInterfaceURL+'requestProductOfferings.wav'],
                ['placeProductOffer.vxml?lang='+lang,   audioInterfaceURL+'placeProductOffer.wav']
                ]


        return render_template(
        'main.vxml',
        interfaceAudioDir = audioInterfaceURL,
        welcomeAudio = 'welcome.wav',
        questionAudio = "mainMenuQuestion.wav",
        options = options)
    else:
        return "give language (not yet implemented)"

@app.route('/requestProductOfferings.vxml')
def requestProductOfferings():
    #process the language
    lang = config.LanguageVars(request.args)


    #if the chosen product has been entered, show results
    if 'product' in request.args:
        choice = request.args['product']
        #TODO keuze in query maken
        #TODO language in query
        results = executeSparqlQuery(
            """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
            PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
            SELECT DISTINCT ?person ?voicelabel_en  WHERE {
                     ?person  rdf:type radiomarche:Person  .
                     ?person radiomarche:contact_fname ?fname .
                     ?person radiomarche:contact_lname ?lname.
                     ?person speakle:voicelabel_en ?voicelabel_en
            }
            LIMIT 10"""

            )
        return render_template(
            'result.vxml',
            interfaceAudioDir = lang.audioInterfaceURL,
            messageAudio = 'presentProductOfferings.wav',
            redirect = 'main.vxml?lang='+lang.language,
            results = results)


    #if no choice was made, offer choices of products to get offerings from
    choices = executeSparqlQuery(
        """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
        PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
        SELECT DISTINCT ?person ?voicelabel_en  WHERE {
                 ?person  rdf:type radiomarche:Person  .
                 ?person radiomarche:contact_fname ?fname .
                 ?person radiomarche:contact_lname ?lname.
                 ?person speakle:voicelabel_en ?voicelabel_en
        }
        LIMIT 10"""

        )
    #add the url of this page to the links, so the user gets the results
    #also keep the language
    for choice in choices:
        choice[0] = 'requestProductOfferings.vxml?lang='+lang.language+'&product=' + choice[0]

    return render_template(
    'menu.vxml',
    options = choices,
    interfaceAudioDir = lang.audioInterfaceURL,
    questionAudio = "chooseYourProduct.wav"
    )

@app.route('/placeProductOffer.vxml')
def placeProductOffer():

    #if user, product, location and price are set, update data in store
    if 'user' in request.args and 'product' in request.args and 'location' in request.args and 'price' in request.args:
        user = request.args['user']
        product = request.args['product']
        location = request.args['location']
        price = request.args['price']

        #TODO keuze in query maken
        results = executeSparqlQuery(
            """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
            PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
            SELECT DISTINCT ?person ?voicelabel_en  WHERE {
                     ?person  rdf:type radiomarche:Person  .
                     ?person radiomarche:contact_fname ?fname .
                     ?person radiomarche:contact_lname ?lname.
                     ?person speakle:voicelabel_en ?voicelabel_en
            }
            LIMIT 10"""

            )
        return render_template(
            'result.vxml',
            interfaceAudioDir = config.interfaceURL,
            messageAudio = 'presentProductOfferings.wav',
            redirect = 'main.vxml',
            results = results)


    #if no choice was made, offer choices of products to get offerings from
    choices = executeSparqlQuery(
        """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
        PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
        SELECT DISTINCT ?person ?voicelabel_en  WHERE {
                 ?person  rdf:type radiomarche:Person  .
                 ?person radiomarche:contact_fname ?fname .
                 ?person radiomarche:contact_lname ?lname.
                 ?person speakle:voicelabel_en ?voicelabel_en
        }
        LIMIT 10"""

        )
    for choice in choices:
        choice[0] = 'placeProductOffer.vxml?choice=' + choice[0]
    return render_template(
    'menu.vxml',
    options = choices,
    interfaceAudioDir = config.interfaceURL,
    questionAudio = "chooseYourProduct.wav"
    )


if __name__ == '__main__':
    app.run(host="0.0.0.0",debug=config.debug)
