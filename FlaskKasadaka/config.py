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
    import config
    audioURL = audioURLbase + defaultLanguage + "/"
    audioInterfaceURL = audioURLbase + defaultLanguage + "/interface/"
    language = defaultLanguage

    def __init__(self,languageInit):
        if type(languageInit) is not str and 'lang' in languageInit:
             self.audioURL = config.audioURLbase + languageInit['lang'] + "/"
             self.audioInterfaceURL = config.audioURLbase + languageInit['lang'] + "/interface/"
             self.language = languageInit['lang']
        elif type(languageInit) is str:
            self.audioURL = config.audioURLbase + languageInit + "/"
            self.audioInterfaceURL = config.audioURLbase + languageInit + "/interface/"
            self.language = languageInit

    def __str__(self):
        return language
