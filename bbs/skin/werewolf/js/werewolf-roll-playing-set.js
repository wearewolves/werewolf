function openModal() {
	var modal = document.getElementById('modal-window');
	var RPSetBtn = document.getElementById("RPSetBtn");
	var closeBtn = document.getElementsByClassName("close")[0];
	
	// Open the modal
	modal.style.display = "block";
	// Initialize the list
	initOpenList();
	
	// Close the modal
	closeBtn.onclick = function() {
		modal.style.display = "none";
	}
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
}

function initOpenList() {
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the first tab, and add an "active" class to the button that opened the tab
    tabcontent[0].style.display = "block";
    tabcontent[0].className += " active";
}

function openList(evt, listName) {
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(listName).style.display = "block";
    evt.currentTarget.className += " active";
}

function searchRPSet() {
    var input, filter, ul, li, a, i;
	
    input = document.getElementById("RPSetInput");
    filter = input.value.toUpperCase();
	
	tablinks = document.getElementsByClassName("tablinks");
	tabcontentID = (tablinks[0].className).indexOf("active") !== -1 ? "listByTimeSort" : "listByAscendingSort";
	tabcontent = document.getElementById(tabcontentID);
	
    li = tabcontent.getElementsByTagName("li");

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function selectRPSet(no, name) {
	var characterSet, characterSetName;
	var modal;

	characterSet = document.getElementById("characterSetInput");
	characterSetName = document.getElementById("characterSetNameInput");
	
	modal = document.getElementById('modal-window');
	
	characterSet.value = no;
	characterSetName.value = name;
	
	modal.style.display = "none"
}