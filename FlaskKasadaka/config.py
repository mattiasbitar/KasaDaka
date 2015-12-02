#Change these variables according to your installation

#Sparql endpoint
sparqlURL = "http://127.0.0.1:3020/sparql/?query="

#default language
defaultLanguage = "en"
#audio files location
audioURLbase = "http://127.0.0.1/audio/"

#debug mode
debug=True

##DO NOT EDIT BELOW THIS line

class LanguageVars(object):
    audioURL = audioURLbase + defaultLanguage + "/"
    audioInterfaceURL = audioURLbase + defaultLanguage + "/interface/"
    language = defaultLanguage

    def __init__(languageInit):
        if type(languageInit) is not str and 'lang' in languageInit:
             audioURL = audioURLbase + languageInit['lang'] + "/"
             audioInterfaceURL = audioURLbase + languageInit['lang'] + "/interface/"
             language = languageInit['lang']
        elif type(languageInit) is str:
            audioURL = audioURLbase + languageInit + "/"
            audioInterfaceURL = audioURLbase + languageInit + "/interface/"
            language = languageInit

    def __str__(self):
        return language
