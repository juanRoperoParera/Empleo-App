/* Menu hamburguesa */

const abrirMenu = document.querySelector(".abrirMenu");
const cerrarMenu = document.querySelector(".cerrarMenu");
const nav = document.querySelector("nav");


abrirMenu.addEventListener("click",
()=>{
  abrirMenu.classList.add("oculto");
  nav.classList.add("completo");
  cerrarMenu.classList.remove("oculto");

  cerrarMenu.addEventListener("click",
  ()=>{
    nav.classList.remove("completo");
    cerrarMenu.classList.add("oculto");
    abrirMenu.classList.remove("oculto");
  });
})