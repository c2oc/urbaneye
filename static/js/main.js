/* account stuff */
function isLogged() {
    $.ajax({
        type: "post",
        url: "/../api/sessionHandler.php",

        success: function(response) {
            if (JSON.parse(response).usersession) {
                document.querySelector("#login-nav").style.display = "none";
                document.querySelector("#user-profile").style.display = "inherit";
            } else {
                document.querySelector("#login-nav").style.display = "inline-block";
                if (document.title.includes("Favourites")) {
                    window.location.href = "../index.html";
                }
            }
        }
    });
}

function loggedIn() {
    document.querySelector("#login-nav").style.display = "none";
    document.querySelector("#user-profile").style.display = "inherit";
}

function logout() {
    $.ajax({
        type: "post",
        url: "/../api/logout.php",

        success: function(response) {
            if (JSON.parse(response).logout) {
                location.reload();
            }
        }
    });
}
function signup (){
    $('#signup-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "/../api/signup.php",
            data: $(this).serialize(),

            success: function(response) {
                if (JSON.parse(response).e_isTaken) {
                    $("#takenMail").show("fast");
                } else {
                    $("#takenMail").hide("fast");
                }
                if (JSON.parse(response).u_isTaken) {
                    $("#takenUsername").show("fast");
                } else {
                    $("#takenUsername").hide("fast");
                }
                if (JSON.parse(response).registered) {
                    $("#signup").hide("fast");
                    loggedIn();
                }
            }
        });
    });
}
function login (){
    $('#login-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "/../api/login.php",
            data: $(this).serialize(),
            success: function(response) {
                if (JSON.parse(response).success) {
                    $("#incorrectDataLogin").hide("fast");
                    $("#login").hide("fast");
                    loggedIn();
                } else {
                    $("#incorrectDataLogin").show("fast");
                }
            }
        });
    });
}
/* Favourites */
function favouritesHandler(generated, toGenerate){
    return $.ajax({
        type: "post",
        url: "/../api/userHandler.php",
        data: {pageRequested: "favourites", alreadyGenerated: generated, toGenerate: generated+toGenerate},
        success: function (response) {
            cityWildcardGen(generated, JSON.parse(response).favourites);
        },
        error: function () {
            console.log("JSON error: couldn't reach the server")
        }
    });
}
/* userDrop */
function userDropHandler(){
    return $.ajax({
        type: "post",
        url: "/../api/userHandler.php",
        data: {pageRequested: "user-drop"},
        success: function (response) {
            const drop = document.querySelector("#profile-drop");
            drop.querySelector("#drop-username").innerHTML = JSON.parse(response).username;
            drop.querySelector("#drop-email").innerHTML = JSON.parse(response).email;
            drop.querySelector("#drop-propic").src = "../assets/img/users/" + JSON.parse(response).propic;
        },
        error: function () {
            console.log("JSON error: couldn't reach the server (user-drop)")
        }
    });
}
function userDrop(){
    $.when(userDropHandler()).done(function(){
        $("#profile-drop").show("fast");
    });
}
/* live search */
function liveSearch(maxResults){
    $('#live-search').on('keyup', function () {
        let citySearched = $(this).val();
        if (citySearched.length > 2 && !isBlank(citySearched)) {
            $("#search-loader").hide("fast");
            $.ajax({
                type: "post",
                url: "/../api/livesearch.php",
                data: $(this).serialize(),

                success: function(response) {
                    destroyChildren();
                    let results = [];
                    for (let i = 0; i < maxResults; i++){
                        results[i] = JSON.parse(response)[i];
                    }
                    showResults(results);
                    $("#search-results").show("fast");
                }
            });
        } else if (isBlank(citySearched)) {
            $("#search-loader").hide("fast");
            $("#search-results").hide("fast");
        } else {
            $("#search-loader").show("fast");
            $("#search-results").hide("fast");
        }
    });
}
function isBlank(str){
    return (!str || /^\s*$/.test(str));
}
function showResults(res) {
    const resultPop = document.querySelector("#search-results");
    let clones = [], filteredRes = [];
    for (let i = 0; i < res.length; i++) {
        if (res[i] !== undefined) {
            filteredRes.push(res[i]);
        }
    }
    if (filteredRes.length) {
        $("#result-lost").hide("show");
        for (let i = 0; i < filteredRes.length; i++) {
            clones.push(document.querySelector("#single-result").cloneNode(true));
            clones[i].setAttribute('id', "gen-" + i);
            if (i === filteredRes.length - 1){
                clones[i].classList.remove("border-b");
            }
            clones[i].style.display = "inherit";
            clones[i].querySelector("#result-name").innerHTML = filteredRes[i][0];
            clones[i].querySelector("#result-name").href = "../pages/result.php?q=" + filteredRes[i][1] + "&t=" + filteredRes[i][2];
            clones[i].querySelector("#search-default").style.display = "inherit";
            resultPop.appendChild(clones[i]);
        }
    } else {
        $("#result-lost").show("show");
    }
}
function destroyChildren(){
    const father = document.querySelector("#search-results");
    let i = 0;
    while ($("#gen-"+i).length) {
        let child = father.querySelector("#gen-"+i);
        child.parentNode.removeChild(child);
        i++;
    }
}
/* city Wildcard */
function scoreColour (grade, isLine=false) {
    if (!isLine) {
        if (grade >= 70) {
            return "bg-green-500";
        } else if (grade < 70 && grade > 50) {
            return "bg-custom-warning";
        } else {
            return "bg-custom-red";
        }
    } else {
        if (grade >= 70) {
            return "#2AFF44";
        } else if (grade < 70 && grade > 50) {
            return "#EDBF1B";
        } else {
            return "#E82525";
        }
    }
}
function cityWildcardID (cloneID){
    for (let i = cloneID.length; i > 0; i--) {
        if (cloneID[i] === "-") {
            return cloneID.slice(i+1);
        }
    }
}
function cityWildcardHandler(alreadyGen, nextGen) {
    return $.ajax({
        type: "post",
        url: "/../api/wildcardsHandler.php",
        data: {isReview: 0, alreadyGenerated: alreadyGen, toGenerate: nextGen + alreadyGen},
        success: function (response) {
            cityWildcardGen(alreadyGen, JSON.parse(response).cities);
        },
        error: function () {
            console.log("JSON error: couldn't reach the server")
        }
    });
}

function cityWildcardGen(n_gen, data) {
    const weatherType = ["stormy", "foggy", "sunny", "snowy", "rainy"];
    let clone = null;
    let overallScores = [];
    let isTrending = 0;
    for (let i = 0; i < data.length; i++) {
        overallScores = [data[i][2][0], data[i][2][1], data[i][2][2], data[i][2][3], data[i][2][4]]; // taxes, environment, security, col, overall score
        clone = document.querySelector("#single-city").cloneNode(true);
        clone.setAttribute('id', "city-gen-" + (n_gen+i));
        clone.querySelector("#city-bg").src = "../assets/img/cities/" + data[i][3];
        clone.querySelector("#city-page").href = "../pages/result.php?q=" + data[i][4] + "&t=city";
        clone.querySelector("#city-shadow-bg").setAttribute('id', "city-shadow-bg-" + (n_gen+i));
        clone.querySelector("#city-info").setAttribute('id', "city-info-" + (n_gen+i));
        clone.querySelector("#city-temperature").setAttribute('id', 'city-temperature-' + (n_gen+i));
        clone.querySelector("#grade-progress").setAttribute('id', 'grade-progress-' + (n_gen+i));

        clone.querySelector("#overall-score").setAttribute('id', 'overall-score-' + (n_gen+i));
        clone.querySelector("#overall-taxes").setAttribute('id', 'overall-taxes-' + (n_gen+i));
        clone.querySelector("#overall-col").setAttribute('id', 'overall-col-' + (n_gen+i));
        clone.querySelector("#overall-security").setAttribute('id', 'overall-security-' + (n_gen+i));
        clone.querySelector("#overall-environment").setAttribute('id', 'overall-environment-' + (n_gen+i));


        clone.querySelector("#overall-score-" + (n_gen+i)).innerHTML = Math.floor(overallScores[4]).toString();
        clone.querySelector("#overall-score-line").setAttribute('id', 'overall-score-line-' + (n_gen+i));
        clone.querySelector("#overall-score-line-" + (n_gen+i)).style.stroke = scoreColour(Math.floor(overallScores[4]), true);

        clone.querySelector("#overall-taxes-" + (n_gen+i)).classList.replace(clone.querySelector("#overall-taxes-" + (n_gen+i)).classList.item(2), scoreColour(overallScores[0]));
        clone.querySelector("#overall-taxes-" + (n_gen+i)).style.width = overallScores[0] + '%';

        clone.querySelector("#overall-environment-" + (n_gen+i)).classList.replace(clone.querySelector("#overall-environment-" + (n_gen+i)).classList.item(2), scoreColour(overallScores[1]));
        clone.querySelector("#overall-environment-" + (n_gen+i)).style.width = overallScores[1] + '%';

        clone.querySelector("#overall-security-" + (n_gen+i)).classList.replace(clone.querySelector("#overall-security-" + (n_gen+i)).classList.item(2), scoreColour(overallScores[2]));
        clone.querySelector("#overall-security-" + (n_gen+i)).style.width = overallScores[2] + '%';

        clone.querySelector("#overall-col-" + (n_gen+i)).classList.replace(clone.querySelector("#overall-col-" + (n_gen+i)).classList.item(2), scoreColour(overallScores[3]));
        clone.querySelector("#overall-col-" + (n_gen+i)).style.width = overallScores[3] + '%';


        clone.querySelector("#city-name").innerHTML = data[i][0];
        clone.querySelector("#country-name").innerHTML = data[i][1];
        clone.querySelector("#city-temperature-" + (n_gen+i)).innerHTML = ((Math.random()*30).toFixed(0))*(Math.round(Math.random()) * 2 - 1).toString() + "°";
        isTrending = Math.floor(Math.random() * 2); //codice esempio, dovrebbe starci qualche roba con ajax
        // magari da far apparire in base ad una crescita esponenziale di click/ricerche?
        if (isTrending) {
            clone.querySelector("#isTrending").style.display = "block";
            clone.querySelector("#notTrending").style.display = "none";
        } else {
            clone.querySelector("#isTrending").style.display = "none";
            clone.querySelector("#notTrending").style.display = "block";
        }
        clone.style.display = "inherit";
        $("." + weatherType[2]).show();
        document.querySelector("#cities-section").appendChild(clone);
    }
}
function cityWildcardHovered(cloneID, isHovered) {
    const id = cityWildcardID(cloneID);
    const cardShadow = document.querySelector("#city-shadow-bg-" + id);
    if (isHovered) {
        cardShadow.classList.replace("opacity-50", "opacity-60");
        document.querySelector("#city-info-" + id).style.display = "none";
        document.querySelector("#grade-progress-"+id).style.display = "flex";
    } else {
        cardShadow.classList.replace("opacity-60", "opacity-40");
        document.querySelector("#grade-progress-"+id).style.display="none";
        document.querySelector("#city-info-"+id).style.display="flex";
    }
}
/* reviews */
function reviewWildcardHandler() {
    return $.ajax({
        type: "post",
        url: "/../api/wildcardsHandler.php",
        data: {isReview: 1},
        success: function (response) {
            reviewWildcardGen(JSON.parse(response).reviews);
        },
        error: function () {
            console.log("JSON error: couldn't reach the server")
        }
    });
}
function reviewWildcardGen(data) {
    let clone = null;
    for (let i = 0; i < data.length; i++) {
        clone = document.querySelector("#single-review").cloneNode(true);
        clone.setAttribute('id', "review-gen-" + i);
        clone.querySelector("#review-date").innerHTML = data[i][0];
        clone.querySelector("#review-username").innerHTML = data[i][1];
        clone.querySelector("#review-propic").src = "../assets/img/users/" + data[i][2];
        clone.querySelector("#review-country").innerHTML = data[i][3];
        clone.querySelector("#review-city").innerHTML = data[i][4];
        clone.querySelector("#review-score").innerHTML = Math.floor(data[i][5]).toString();
        clone.querySelector("#review-score").classList.replace(0, scoreColour(Math.floor(data[i][5])));
        clone.querySelector("#review-text").innerHTML = data[i][6];
        clone.style.display = "inherit";
        document.querySelector("#review-section").appendChild(clone);
    }
}