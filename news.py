#-*- coding: utf-8 -*-

# TODO Mettre les commentaires en anglais

import webbrowser
import Tkinter as tk
from Tkinter import *
from ScrolledText import *

def liens(lines):    # [Audit][REVIEW] Virer les espaces après le : et sauter une ligne avant le def
    links = []
    for line in lines:
        if line.startswith("http"):
            links.append(line)

    return links



#Ouverture du fichier à consulter# [Audit][REVIEW] Trop de saut de ligne

with open("logs/NewsletterLog.txt", "r") as newsletter:
    news = newsletter.readlines()


link=liens(news)# [Audit][REVIEW] Espace autour de l'opérateur

#création de la fenêtre de visualisation de la newsletter TOTEM
fenetre = tk.Tk()
fenetre.title("TOTEM")

#Création de la zone Texte

texte = ScrolledText(fenetre, width = 150, height = 35, font = "Arial 10", relief = "groove")
i=0# [Audit][REVIEW] Espace autour de l'opérateur

for line in news :# [Audit][REVIEW] Espace avant :
	if "http" in line :# [Audit][REVIEW] Espace avant :
		texte.insert(tk.END, line , i) # [Audit][REVIEW] Espace avant ,
		texte.tag_config(i, foreground="blue", underline=1)
		texte.tag_bind(i, '<Button-1>', lambda e, i=i: webbrowser.open(link[i], new=0, autoraise=True))
		texte.pack()
		i+=1# [Audit][REVIEW] Espace autour de l'opérateur

	else :# [Audit][REVIEW] Espace avant :
		texte.insert(tk.END, line)
		texte.pack()

#texte.pack()

#Ecriture des données
texte.config(state = NORMAL)    #Permettre l'écriture
#texte.insert("2.0", line)       #Gestion de l'insertin des lignes  #1 = 1ere ligne  0 = 1er caractere de la ligne
texte.config(state = DISABLED)  #Interdire les modifications


#bouton de sortie
tk.Button(fenetre, text="Quitter", command=fenetre.destroy).pack()

fenetre.mainloop()

#fermeture du fichier
newsletter.close()
