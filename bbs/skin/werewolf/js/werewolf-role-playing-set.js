function openModal() {
	var modal = document.getElementById("modal-window");
	var RPSetBtn = document.getElementById("RPSetBtn");
	var closeX = document.getElementById("closeX");
	
	// Open the modal
	modal.style.display = "block";
	// Initialize the list
	initOpenList();
	
	// Close the modal
	closeX.onclick = function() {
		modal.style.display = "none";
	}
	
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if(event.target == modal) {
			modal.style.display = "none";
		}
	}
}

function openModalCustomed(selectIndex, selectList) {
	openModal();
	
	var posY = $("#listByTimeSort .CS" + selectIndex.toString(10)).offset().top - $("#listByTimeSort .CS0").offset().top;
	
	// Set the used list
	if(selectList == 1) {
		var tabcontent, tablinks;
		
		tabcontent = document.getElementsByClassName("tabcontent");
		tablinks = document.getElementsByClassName("tablinks");
		
		tabcontent[0].style.display = "none";
		tablinks[0].className = tablinks[0].className.replace(" active", "");
		
		tabcontent[1].style.display = "block";
		tablinks[1].className += " active";
		
		posY = $("#listByAscendingSort .CS" + selectIndex.toString(10)).offset().top - $("#listByAscendingSort .CS0").offset().top;
	}
	
	// Set scrollbar's position
	$(".modal-content").scrollTop(posY);
}

// Initialize lists on modal window. Open the default(first) list.
function initOpenList() {
    var tabcontent, tablinks;
    var input, li;
	var i, j;
	
	// Clear text input
    input = document.getElementById("RPSetInput");
    input.value = "";

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
		
		// Initialize list display option
		li = tabcontent[i].getElementsByTagName("li");
		for (j = 0; j < li.length; j++) {
			li[j].style.display = "";
		}
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the first tab, and add an "active" class to the button that opened the tab
    tabcontent[0].style.display = "block";
    tablinks[0].className += " active";
}

function openList(evt, listName) {
    var tabcontent, tablinks;
    var input, li;
	var i, j;
	
	// Clear text input
    input = document.getElementById("RPSetInput");
    input.value = "";

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
		
		// Initialize list display option
		li = tabcontent[i].getElementsByTagName("li");
		for (j = 0; j < li.length; j++) {
			li[j].style.display = "";
		}
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
    var input, filter, tablinks, tabcontentID, tabcontent, li;
	var i;
	
    input = document.getElementById("RPSetInput");
    filter = input.value.toUpperCase();
	
	// Find active list
	tablinks = document.getElementsByClassName("tablinks");
	tabcontentID = tablinks[0].className.indexOf(" active") !== -1 ? "listByTimeSort" : "listByAscendingSort";
	tabcontent = document.getElementById(tabcontentID);
	
    li = tabcontent.getElementsByTagName("li");
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        if (li[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

// Set selected item
function selectRPSet(no, name) {
	var characterSet, characterSetName, modal;

	characterSet = document.getElementById("characterSetInput");
	characterSetName = document.getElementById("characterSetNameInput");
	
	modal = document.getElementById("modal-window");
	
	// Set characterSet no & name
	characterSet.value = parseInt(no, 10);
	characterSetName.value = name;
	
	// Close the modal
	modal.style.display = "none";
}