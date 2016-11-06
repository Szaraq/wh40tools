/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var fileInput = $('#rozpa');

function handleFileSelect() {
  if (!window.FileReader) {
        alert('Your browser is not supported');
        return false;
    }
    var input = fileInput.get(0);

    // Create a reader object
    var reader = new FileReader();
    if (input.files.length) {
        var textFile = input.files[0];
        // Read the file
        reader.readAsText(textFile);
        // When it's loaded, process it
        $(reader).on('load', processFile);
    } else {
        alert('Please upload a file before continuing')
    } 
}

function processFile(e) {
    var file = e.target.result,
        results;
    if (file && file.length) {
        var punkty = file.match(/[a-z0-9_()]+</i).toString().match(/[0-9]+/i);
        if(parseInt(punkty) > parseInt(document.getElementById('punkty').value)) {
            alert("Rozpa ma za dużo punktów");
        }
    }
}

document.getElementById('rozpa').addEventListener('change', handleFileSelect, false);