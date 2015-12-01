import config
from getSparql import executeSparqlQuery
import kasadaka

identificationQuery = """sadads"""
def handle(state = "",data = ""):
    flow = {
                "" : welcome,
                "welcome" : mainMenu,
                "mainMenu" : mainMenu,

                #when user selected to find offerings, redirect to results, then back to main menu
                "chooseProductToGetOfferingsOf" : giveProductOfferings,
                "giveProductOfferings" : mainMenu,

                #when user selected to put an offer and selected the product, ask for price, then identification, then to confir, return to menu
                # "" : enterPrice,
                # "enterPrice" : identification,
                # "identification" : confirm,
                # "confirm" : mainMenu,

            }
    return flow[state](data)

    #welcome the user
    #LATER identify the user

    #about which product would you like information?

def welcome(data):
    return kasadaka.message("welcome","interface/welcome.wav")

def mainMenu(data):

    options = [
            ['chooseProductToGetOfferingsOf','interface/chooseProductToGetOfferingsOf.wav'],
            ['chooseProductToMakeOffer','interface/chooseProductToMakeOffer.wav']
            ]
    if data != "":
        for possibleOption in options:
            if data == possbleOption[0]:
                return possibleOption[0]()
    mainMenuAudio = 'interface/choosewhatyouwanttodo.wav'
    return kasadaka.menu('mainMenu',mainMenuAudio,options)

def chooseProductToGetOfferingsOf(data):
    query = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
                PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
                    SELECT DISTINCT ?person ?voicelabel_en  WHERE {
                    ?person          rdf:type radiomarche:Person  .
                    ?person radiomarche:contact_fname ?fname .
             ?person radiomarche:contact_lname ?lname.
      ?person speakle:voicelabel_en ?voicelabel_en
                       }
              LIMIT 5"""

    questionAudio = "interface/samplequestion.wav"
    return kasadaka.menu('chooseProductToGetOfferingsOf',questionAudio,executeSparqlQuery(query))

def giveProductOfferings(data):

    product = data
    query = """""" + product + """"""

    resultAudio = 'interface/announceresults.wav'
    return kasadaka.result('giveProductOfferings',resultAudio,executeSparqlQuery(query))
