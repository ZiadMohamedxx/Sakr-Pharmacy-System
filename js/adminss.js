function activateSearch() {
  const searchInput = document.getElementById('searchInput');
  searchInput.focus();
}

document.getElementById('searchInput').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    performSearch(this.value);
  }
});

function performSearch(query) {
  if (!query.trim()) return;
  console.log('Searching for:', query);
}

function validateFormAddProduct(element){ 
  var regex = {
  };

  if (regex[element.id].test(element.value)) {
    element.classList.add("is-valid");
    element.nextElementSibling.classList.add("d-none");
    element.classList.remove("is-invalid");
  } else {
    element.classList.add("is-invalid");
    element.classList.remove("is-valid");
    element.nextElementSibling.classList.remove("d-none");
  }
}

function validateInput() {
  const input = document.getElementById('category');
  const datalist = document.getElementById('searchOptions');
  const options = Array.from(datalist.options).map(option => option.value);

  if (!options.includes(input.value)) {
    error.textContent = 'Please select a valid option from the list.';
    input.focus();
    return false;
  }

  error.textContent = '';
  return true;
}

function getSectionId(id) {
  localStorage.setItem("selectedSection", id);
  window.location.href = "tst.php";
}