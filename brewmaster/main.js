function filterSelection(category) {

let items = document.getElementsByClassName("filter");

for (let i = 0; i < items.length; i++) {

items[i].style.display = "none";

if (category === "all" || items[i].classList.contains(category)) {
items[i].style.display = "block";
}

}

}


document.getElementById("recipeForm")?.addEventListener("submit", function(e){

e.preventDefault();

let name = document.getElementById("name").value;
let email = document.getElementById("email").value;

if(name === "" || email === ""){

document.getElementById("message").innerHTML = "Please fill all fields";
document.getElementById("message").style.color = "red";

}

else{

document.getElementById("message").innerHTML = "Recipe submitted successfully!";
document.getElementById("message").style.color = "green";

}

});