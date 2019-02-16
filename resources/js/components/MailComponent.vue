<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="/">Boîte mail</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" v-on:click="noFiltre">Tous</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-on:click="filtreSoci">Sociétés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-on:click="filtreSpam">Spams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-on:click="filtreNonSoci">Autres</a>
                    </li>
                </ul>
            </div>
            <input class="rounded p-1 mr-5 float-right" type="search" placeholder="Search" aria-label="Search" v-model="nomSociete">
        </nav>
        <div class="row position-fixed" >
            <div class="col-6 col-md pre-scrollable mt-1 " style="min-width: 26.6rem; min-height: 93vh">
                <div :key="unMail.id" v-for="(unMail, index) in listeMails" >
                    <div class="text-left card rounded-0 bg-light text-dark border-dark" style="width: 25rem; height: 2.4rem;" v-if="datePrece[index] && (nomSociete == unMail.societe || nomSociete == '' )">
                        <div class="card-body pt-2 font-weight-bold">
                                {{ unMail.jourFR }} {{ unMail.annee }}
                        </div>
                    </div>
                    <button v-if="nomSociete == unMail.societe || nomSociete == ''" v-on:click="afficheMail(unMail)" class="text-left card rounded-0 bg-secondary text-white border-dark" style="width: 25rem; height: 5.5rem;"> 
                        <div  class="card-body pt-3">
                            <div>De : {{ unMail.mailPersonal }} 
                                <span v-if="unMail.societe" class="badge badge-light ml-3">
                                    Société : {{ unMail.societe }}
                                </span> 
                            </div>
                            <div class="row">
                                <div class="col text-truncate">Objet : {{ unMail.objet }}</div>
                                <div class="col text-right "><small>{{ unMail.heure}}</small></div>
                            </div>
                            <div class="card-text text-white-50 text-truncate">
                                {{unMail.contenuTEXT}}
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div  v-if ="message" class="col-12 col-sm-6 col-md-8 pre-scrollable mt-1" style="max-height: 93vh">
                <div class="text-left rounded-0 border-dark" style="min-width: 50rem; min-height: 42.3rem;"> 
                    <div  class="card" style="width: 50rem; min-height: 42.3rem;">
                        <div class="card-body py-5 px-5">
                            <div class="card-title pt-1 pb-3 pl-3 border-bottom">
                                <button v-on:click="supprimerVerif()" class="rounded-circle ml-1 btn btn-white" style="color: dark;" >
                                        <i class="fas fa-plus"></i>
                                </button>
                                <h6>De : {{ leMail.mailFull }}
                                    <button v-on:click="donneeForm()" class="rounded-circle ml-1 btn btn-white" style="color: dark;" v-if="!(leMail.contact) && constrForm.success === false">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <span class="text-success  font-weight-bold"  v-if="constrForm.success" >Ajout effectué !</span>
                                </h6>
                                
                                <h6 v-if="leMail.societe">
                                    Société : {{ leMail.societe }}
                                </h6>
                                <small>
                                    {{ leMail.jourFR }} {{leMail.annee}} à {{leMail.heure}}
                                </small>
                            </div>
                            <p class="card-text pt-3" v-html="leMail.contenuHTML">
                                {{ leMail.contenuHTML }}
                            </p>
                        </div>
                    </div>
                </div>
            </div> 
            <div  v-else class="col-12 col-sm-6 col-md-8 mt-1 invisible" style="max-height: 93vh">
                <div class="text-left rounded-0 border-dark" style="min-width: 50rem; min-height: 42.3rem;"> 
                    <div  class="card" style="width: 50rem; min-height: 42.3rem;">
                    </div>
                </div>
            </div> 
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h3 class="modal-title">
                            Ajout contact
                        </h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
    
                    <form @submit.prevent="EnvoieContact()">
                        <div class="px-4 py-2 rounded">
                            <div class="form-group mb-2">
                                 <label class="sr-only" for="inputMail">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <div class="input-group-text">@</div>
                                        </div>
                                            <input type="text" id="inputMail" class="form-control" placeholder="Email :" v-model="formContact.email" required>
                                    </div>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" placeholder="Prénom :" v-model="formContact.prenom" required>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" placeholder="Nom :" v-model="formContact.nom" required>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control" placeholder="Fonction :" v-model="formContact.fonction" required>
                            </div>
                            <div v-if="!afficheClient" class="form-row  mb-2">
                                <div class="form-group col-md-8">
                                    <select v-model="formContact.client" class="form-control">
                                        <option value="">--</option>
                                        <option :key="unClient.id" v-for="unClient in listeClient" :value="unClient.id">{{ unClient.societe }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <span v-on:click="Client(leMail)" class="btn" style="color: grey;" v-if="!(leMail.contact) && this.afficheClient == false">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                            <div v-if="afficheClient" class="form-row  mb-2">
                                <div  class="form-group col-md-8">
                                    <input type="text" class="form-control" placeholder="Nom société :" v-model="formClient.nomSociete" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <span  v-on:click="Client(leMail)" class="btn" style="color: grey;" v-if="!(leMail.contact) && this.afficheClient == true">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                                
                            </div> 
                        </div>
                        <div>
                            <button type="submit" class="form-control rounded-0 btn btn-success" >Ajouter</button>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade  " id="supprimer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                            <h5 class="mx-auto my-3">
                                Voulez vous vraiment supprimer ce mail ?
                            </h5>          
                    </div>
                    <div class="btn-group btn-group-toggle">
                        <button class="btn flex-center rounded-0 btn-warning" data-dismiss="modal">
                            Annuler
                        </button>
                        <button v-on:click="supprimer()" class="btn flex-center rounded-0 btn-success">
                            Supprimer
                        </button>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['lesMails', 'listeClient'],
        data()
        {
            return{
                formContact:
                {
                    prenom : "",
                    nom : "",
                    email : "",
                    fonction : "",
                    client : "",
                }, 
                formClient:
                {
                    nomSociete : "",
                },
                constrForm:
                {
                    mailPersonal : "",
                    success : false
                },      
                message : false,
                leMail: [],
                jour: [],
                ok: false,
                listeMails: this.lesMails,
                datePrece : [],
                nomSociete: "",
                afficheClient: false,
            }
        },  
        methods: {

            Client: function(leMail)
            {
                this.afficheClient = !this.afficheClient
            },

            EnvoieContact: function()
            {
                axios.post('/ajoutContact', 
                {
                    prenom : this.formContact.prenom,
                    nom : this.formContact.nom,
                    email : this.formContact.email,
                    fonction : this.formContact.fonction,
                    client : this.formContact.client,
                    nomSociete : this.formClient.nomSociete,
                    afficheClient : this.afficheClient,
                })
                .then(({result}) => 
                {
                    this.constrForm.success = true
                    this.formContact.nom = ""
                    this.formContact.prenom = ""
                    this.formContact.email = ""
                    this.formContact.fonction = ""
                    this.formContact.client = ""
                    this.afficheClient = false  
                    $('#myModal').modal('hide')            
                    
                })
                .catch(error => 
                {
                    console.log("ERREUR! L'envoie des données en base a échoué !")
                })      
            },   

            afficheMail: function (unMail) 
            {
                this.constrForm.success = false
                if(this.leMail !== unMail){
                    this.message = true
                    this.leMail =  unMail
                }
                else{
                    this.message = !this.message
                }
                 
            },

            donneeForm: function(){
                this.constrForm.success = false
                this.formContact.email = this.leMail.expediteur
                $('#myModal').modal('show')    
            },

            supprimerVerif: function(){
                $('#supprimer').modal('show')    
            },

            supprimer: function(){
                axios.post('/supprimer',
                {
                    idMail : this.leMail.id
                })
            },

            noFiltre: function () 
            {
                axios.get('/noFiltre')
                .then( response => 
                {
                    this.listeMails = response.data.noFiltre
                    console.log("Success recup info")         
                })
                .then( () => 
                {
                    this.ajoutDateFR();
                    console.log("Success function") 
                }) 
                .catch(error => 
                {
                    console.log('ERROR!')
                }) 
            },

            filtreSoci: function () 
            {
                axios.get('/filtreSoci')
                .then( response => 
                {
                    this.listeMails = response.data.filtreSoci
                    console.log("Success recup info")         
                })
                .then( () => 
                {
                    this.ajoutDateFR();
                    console.log("Success function") 
                }) 
                .catch(error => 
                {
                    console.log('ERROR!')
                }) 
            },
            
            filtreSpam: function () 
            {
                alert('SPAM !!!')
            },

            filtreNonSoci: function () 
            {
                 axios.get('/filtreNonSoci')
                .then( response => 
                {
                    this.listeMails = response.data.filtreNonSoci
                    console.log("Success recup info")         
                })
                .then( () => 
                {
                    this.ajoutDateFR();
                    console.log("Success function") 
                }) 
                .catch(error => 
                {
                    console.log('ERROR!')
                }) 
            },

            ajoutDateFR: function() {
                Object.entries(this.listeMails).forEach(([index, unMail]) => 
                {
                    this.jour = unMail.jour.split('-')
                    switch (this.jour[0]) 
                    {

                        case '01':
                            this.jour[0] = 'Janvier';
                            break;
                        case '02':
                            this.jour[0] = 'Février';
                            break;
                        case '03':
                            this.jour[0] = 'Mars';
                            break;
                        case '04':
                            this.jour[0] = 'Avril';
                            break;
                        case '05':
                            this.jour[0] = 'Mai';
                            break;
                        case '06':
                            this.jour[0] = 'Juin';
                            break;
                        case '07':
                            this.jour[0] = 'Juillet';
                            break; 
                        case '08':
                            this.jour[0] = 'Août';
                            break;
                        case '09':
                            this.jour[0] = 'Septembre';
                            break;
                        case '10':
                            this.jour[0] = 'Octobre';
                            break;
                        case '11':
                            this.jour[0] = 'Novembre';
                            break;
                        case '12':
                            this.jour[0] = 'Décembre';
                            break;   
                        default:
                            break;
                    }
                    switch (this.jour[1]) 
                    {

                        case '01':
                            this.jour[1] = '1';
                            break;
                        case '02':
                            this.jour[1] = '2';
                            break;
                        case '03':
                            this.jour[1] = '3';
                            break;
                        case '04':
                            this.jour[1] = '4';
                            break;
                        case '05':
                            this.jour[1] = '5';
                            break;
                        case '06':
                            this.jour[1] = '6';
                            break;
                        case '07':
                            this.jour[1] = '7';
                            break; 
                        case '08':
                            this.jour[1] = '8';
                            break;
                        case '09':
                            this.jour[1] = '9';
                            break;  
                        default:
                            break;
                    }

                    this.jour =  this.jour[1]+' '+ this.jour[0];

                    if(index == 0)
                    {
                        this.datePrece.push(true);
                    }
                    else
                    {
            
                        if( this.listeMails[index - 1].jour == this.lesMails[index].jour)
                        {
                            this.datePrece.push(false);
                        }
                        else
                        {
                            this.datePrece.push(true);
                        }

                    }
                        
                    this.listeMails[index] = Object.assign({jourFR: this.jour}, unMail )
                })
                Object.entries(this.listeMails).forEach(([index, unMail]) => 
                {
                    this.ok = true
                });
            }   
        },
        mounted()
        {
            this.$nextTick(function () 
            {
                this.ajoutDateFR();    
            })
        }     
    }

</script>