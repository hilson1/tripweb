function calculate_item_price() {
  var adult_price = parseInt(document.getElementById("adult_price").value);
  var child_price = parseInt(document.getElementById("child_price").value);
  console.log(adult_price + child_price);
}

calculate_item_price();
