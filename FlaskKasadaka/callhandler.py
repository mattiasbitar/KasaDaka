import config
from getSparql import executeSparqlQuery
import kasadaka

def start(progress = "",data = [""]):
    flow = {
                "" : welcome,
                "welcome" : welcome,
            }
    return flow[progress](data)

    #welcome the user
    #LATER identify the user

    #about which product would you like information?


def welcome(data):
    return kasadaka.message("interface/welcome.wav")
