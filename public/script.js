document.getElementById("registerLink").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("registerForm").style.display = "block";
});

document.getElementById("backToLogin").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("registerForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";

  // Ensure button width remains consistent
  document.querySelector("#loginForm button").style.width = "100%";
});









//i need an event listener for when the barcode is scanned then it will save 
// the barcode scan as a variable and then send it to the server using fetch
//create a profile page, make the user put in their barcode number when registering so i can save it to the database(barcode_number)
//cretae a text field on register page and when the user is signing up, the user inputs barcode infomration

document.getElementById("barcodeInput").addEventListener("input"), function(event) {
  const barcode = event.target.value;
  // Send the barcode to the server using fetch
  fetch('/barcode', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ barcode: barcode })
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}