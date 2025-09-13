
function addCarToLocalStorage(carName, carPrice, carSeats) {
 
  let rentedCars = JSON.parse(localStorage.getItem("rentedCars")) || [];

  let carDetails = {
    name: carName,
    price: carPrice,
    seats: carSeats,
    rentDate: new Date().toLocaleString()
  };

  rentedCars.push(carDetails);

  localStorage.setItem("rentedCars", JSON.stringify(rentedCars));
}

let rentButtons = document.querySelectorAll(".car-rent-button");
rentButtons.forEach((btn) => {
  btn.addEventListener("click", function () {
    let carName = this.closest(".cart").querySelector(".car-name b").innerText;
    let carPrice = this.closest(".cart").querySelector(".price-detail p i.fa-sack-dollar").parentElement.innerText;
    let carSeats = this.closest(".cart").querySelector(".price-detail p i.fa-chair").parentElement.innerText;

    addCarToLocalStorage(carName, carPrice, carSeats);

    alert("You rented " + carName + " for " + carPrice + " with " + carSeats + " seats.");
  });
});

document.getElementById("name-search").addEventListener("keyup", function () {
  let filter = this.value.toLowerCase();
  let cars = document.getElementsByClassName("cart");

  for (let i = 0; i < cars.length; i++) {
    let name = cars[i].querySelector(".car-name p b").innerText.toLowerCase();
    if (name.indexOf(filter) > -1) {
      cars[i].style.display = "";
    } else {
      cars[i].style.display = "none";
    }
  }
});


document.getElementById("seat-search").addEventListener("keyup", function () {
  let filter = this.value.toLowerCase();
  let cars = document.getElementsByClassName("cart");

  for (let i = 0; i < cars.length; i++) {
    let seats = cars[i].querySelector(".price-detail p i.fa-chair").parentElement.innerText.toLowerCase();
    if (seats.indexOf(filter) > -1) {
      cars[i].style.display = "";
    } else {
      cars[i].style.display = "none";
    }
  }
});


document.getElementById("price-search").addEventListener("keyup", function () {
  let filter = this.value.toLowerCase();
  let cars = document.getElementsByClassName("cart");

  for (let i = 0; i < cars.length; i++) {
    let price = cars[i].querySelector(".price-detail p i.fa-sack-dollar").parentElement.innerText.toLowerCase();
    if (price.indexOf(filter) > -1) {
      cars[i].style.display = "";
    } else {
      cars[i].style.display = "none";
    }
  }
});
