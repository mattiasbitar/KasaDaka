
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
    options = [
            ['requestProductOfferings.vxml',config.interfaceURL+'requestProductOfferings.wav'],
            ['chooseProductToMakeOffer',config.interfaceURL+'chooseProductToMakeOffer.wav']
            ]


    return render_template(
    'main.vxml',
    interfaceAudioDir = config.interfaceURL,
     welcomeAudio = 'welcome.wav',
     questionAudio = "mainMenuQuestion.wav",
     options = options)

@app.route('/message.vxml')
def message(state = "",messageURL = "interface/error.wav"):
    return render_template("message.vxml",messageaudio = config.audioURL + messageURL, state = state)

@app.route('/menu.vxml')
def menu(state,questionURL,options):
    return render_template('menu.vxml', state = state, questionaudio = config.audioURL + questionURL, questionaudiodir = config.interfaceURL, results = options)

@app.route('/confirm.vxml')
def confirm(subjectURL):
    return ""

@app.route('/result.vxml')
def result(state,messageAudio,results):
    return render_template('result.vxml',state = state, messageAudio = messageAudio, results = results)


@app.route('/requestProductOfferings.vxml')
def requestProductOfferings():

    #als het product meegegeven wordt, resultaten laten zien (loop back naar main), anders lijst met te kiezen producten
    if 'choice' in request.args:
        choice = request.args['choice']
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
            LIMIT """

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
        LIMIT """

        )
    for choice in choices:
        choice[0] = 'requestProductOfferings.vxml?choice=' + choice[0]
    return render_template(
    'menu.vxml',
    options = choices,
    interfaceAudioDir = config.interfaceURL,
    questionAudio = "chooseYourProduct.wav"
    )




if __name__ == '__main__':
    app.run(host="0.0.0.0",debug=config.debug)
