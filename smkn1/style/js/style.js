function hideSidebar() {

	document.getElementById('sidebar').style.marginLeft = "-250px";
	document.getElementById('konten').style.width = "100vw";
	document.getElementById('hideAndShow').setAttribute('onclick', 'showSidebar()');

}

function showSidebar() {
	
	document.getElementById('sidebar').style.marginLeft = "0px";
	document.getElementById('konten').style.width = "84vw";
	document.getElementById('hideAndShow').setAttribute('onclick', 'hideSidebar()');

}

$(document).ready(function() {
	$('#dataTables').DataTable();
})