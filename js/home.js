 document.getElementById("rent-search").addEventListener("keyup", function () {
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
 
    let rentButtons = document.querySelectorAll(".car-rent-button");
    rentButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        let carName = this.closest(".cart").querySelector(".car-name b").innerText;
        alert("You selected " + carName + " for rent!");
      });
    });