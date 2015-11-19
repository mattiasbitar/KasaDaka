
from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash

from getSparql import executeSparqlQuery

import config
import callhandler

app = Flask(__name__)
@app.route('/')
def index():
    return 'This is the Kasadaka Vxml generator'

@app.route('/message.vxml')
def message(messageURL = "interface/error.wav"):
    return render_template("message.vxml",messageaudio = config.audioURL + messageURL)

@app.route('/menu.vxml')
def menu(questionURL,options):
    return render_template('menu.vxml', questionaudio = config.audioURL + questionURL, questionaudiodir = config.interfaceURL, results = options)

@app.route('/confirm.vxml')
def confirm(subjectURL):
    return ""

@app.route('/result.vxml')
def result(results):
    return ""

@app.route('/post.vxml')
def post():
    return ""


@app.route('/main.vxml')
def main():
    return callhandler.start()
    #return menu("interface/samplequestion.wav",executeSparqlQuery("""PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    #PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
    #PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
    #SELECT DISTINCT ?person ?voicelabel_en  WHERE {
    #          ?person          rdf:type radiomarche:Person  .
    #                     ?person radiomarche:contact_fname ?fname .
    #                                ?person radiomarche:contact_lname ?lname.
    #                                                  ?person speakle:voicelabel_en ?voicelabel_en
    #}
    #LIMIT 5"""))

if __name__ == '__main__':
    app.run(host="0.0.0.0",debug=config.debug)


