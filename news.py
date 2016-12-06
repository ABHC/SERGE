#-*- coding: utf-8 -*-

import webbrowser
import Tkinter as tk
from Tkinter import *
from ScrolledText import *

def liens(lines):    
    links = []
    for line in lines: 
        if line.startswith("http"):
            links.append(line)
 
    return links

				

#Ouverture du fichier à consulter

with open("logs/NewsletterLog.txt", "r") as newsletter:
    news = newsletter.readlines()


link=liens(news)

#création de la fenêtre de visualisation de la newsletter TOTEM
fenetre = tk.Tk()
fenetre.title("TOTEM")

#Création de la zone Texte 

texte = ScrolledText(fenetre, width = 150, height = 35, font = "Arial 10", relief = "groove") 
i=0

for line in news : 
	if "http" in line :
		texte.insert(tk.END, line , i)
		texte.tag_config(i, foreground="blue", underline=1)
		texte.tag_bind(i, '<Button-1>', lambda e, i=i: webbrowser.open(link[i], new=0, autoraise=True))
		texte.pack()
		i+=1
 
	else : 
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