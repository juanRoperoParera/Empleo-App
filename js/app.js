const config = {
    apiKey: "AIzaSyC51xe7QIewSJFv7oawvztRlJjNzlrNt3c",
    authDomain: "hyfel-d5e2e.firebaseapp.com",
    databaseURL: "https://hyfel-d5e2e-default-rtdb.firebaseio.com",
    projectId: "hyfel-d5e2e",
    storageBucket: "hyfel-d5e2e.appspot.com",
    messagingSenderId: "416713967350",
    appId: "1:416713967350:web:d404c322fd418ca2760ed7"
  };


  firebase.initializeApp(config);
  let db = firebase.database();
  
  let caja = document.querySelector("#caja");
  let boton = document.querySelector("#boton-chat");
  let mensajes = document.querySelector("#mensajes");

  let nombreP = document.querySelector("#nombre");
  let nombre = nombreP.innerText;

  let nombrePr = document.querySelector("#nombreProyecto");
  let nombreProyecto = nombrePr.innerText;


 db.ref(nombreProyecto).on("child_added",
    (datos) => {
      let hijo = datos.val();
      let nuevo_mensaje = document.createElement("p");

      if (hijo["mensaje"]["usuario"] === nombre) {
        nuevo_mensaje.classList.add("izq");
        nuevo_mensaje.innerHTML = "<b>Yo:</b>" + hijo["mensaje"]["contenido"];
      } else {
        nuevo_mensaje.classList.add("der");
        nuevo_mensaje.innerHTML = "<b>" + hijo["mensaje"]["usuario"] + ":</b>" + hijo["mensaje"]["contenido"];
      }
      console.log(nuevo_mensaje);

      mensajes.appendChild(nuevo_mensaje);
 
    });


  caja.addEventListener("keypress",
    (e) => {
      let tecla = e.keyCode;
      if (tecla == 13 && caja.value.trim() !== "") {
        db.ref(nombreProyecto).push({ "mensaje": { "contenido": caja.value.trim(), "usuario": nombre } });
        caja.value = "";
        
      }
    });


  boton.addEventListener("click",
    (e) => {

      if (caja.value.trim() !== "") {
        db.ref(nombreProyecto).push({ "mensaje": { "contenido": caja.value.trim(), "usuario": nombre } });
        caja.value = "";
        document.documentElement.scrollTop = document.documentElement.scrollHeight;
      }
    });