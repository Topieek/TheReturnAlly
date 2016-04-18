var playerJSON = [];

function u(t) {
	$("#update").append(t);
}

function ok() {
	u("<span style='color: #00f028'>OK</span><br />");
	return true;
}

function fail() {
	u("<span style='color: red'>FAILED</span><br />");
	return true;
}

function p(n) {
	$("#current").html(n);
	$("#current").trigger("edited");
}

function pp(n) {
	$("#ccurrent").html(n);
	$("#ccurrent").trigger("edited");
}

function gp(c, n) {
	var plus = c + 1;
	p(c);
	$.getJSON("core/new/getPlayersByPage.php?page=" + c, function(data, success, xhr) {
		if(xhr.status == 202) {
			playerJSON = $.merge(playerJSON, data);
			if(plus <= n) {
				gp(plus, n);
			} else {
				$("#current").trigger("finished");
			}
		}
	}).fail(function(){
		fail();
	});
}

function gc(c, n, json) {
	var plus = c + 1;
	pp(plus);
	if(c < n) {
		$.ajax({
			type: "POST",
			url: "core/new/getCitiesByPID.php",
			data: {
				"pid": json[c]['id'],
				"owner": json[c]['owner'],
				"alliantie": json[c]['alliantie']
			},
			success: function(data, status, xhr) {
				console.log(xhr.status);
				if(plus < n) {
					gc(plus, n, json);
				} else {
					$("#ccurrent").trigger("finished");
				}
			}
		});
	}
}

function beforeUnloadFunc() {
	return "";
}

window.onbeforeunload = beforeUnloadFunc;


$(document).ready(function(){
	u("Creating update database... ");
	$.get("core/createUpdate.php",  function(data, success, xhr) {
		if(xhr.status == 202) {
			ok();
			u("Obtaining player page amount... ");
			$.get("core/new/getPlayerPageAmount.php", function(data, success, xhr) {
				if(xhr.status == 202) {
					amount = data - 1;
					var current = 0;
					ok();
					u("Obtaining players (<span id='current'>0</span>/" + amount + ")... ");
					gp(current, amount);
					$("#current").bind("finished", function(){
						ok();
						u("Obtaining cities from players (<span id='ccurrent'>0</span>/" + playerJSON.length + ")...");
						gc(current, playerJSON.length, playerJSON);
						$("#ccurrent").bind("finished", function(){
							ok();
							u("Removing old table... ");
							$.get("core/removeOldDatabase.php",  function(data, success, xhr) {
								if(xhr.status == 202) {
									ok();
									u("Renaming new table... ");
									$.get("core/renameTable.php",  function(data, success, xhr) {
										if(xhr.status == 202) {
											ok();
											u("DONE");
										}
									}).fail(function(){
										fail();
									});
								}
							}).fail(function(){
								fail();
							});
						});
					});
					
				}
			}).fail(function(){
				fail();
			});
		}
	});		
});