/**
* Function responsible for bulding the table of logs
*/
function create_table() {
    var xhttp = new XMLHttpRequest();
    var table = document.getElementById("result_table");
    table.innerHTML = "";
    
    var preview_length = 150;
    var start_date = 0;
    var end_date = 0;
    
    //if the user has given inputs set these values
    if (document.getElementById("start_date").value) {
        start_date = document.getElementById("start_date").value;
    }
    if (document.getElementById("end_date").value) {
        end_date = document.getElementById("end_date").value;
    }
    
    //set text saying the current date range of logs displayed
    if (start_date && end_date) {
        document.getElementById("dates_showing").innerHTML = "Showing logs from " + start_date + " to " + end_date;
    } else if (start_date && !end_date) {
        document.getElementById("dates_showing").innerHTML = "Showing logs after " + start_date;
    } else if (!start_date && end_date) {
        document.getElementById("dates_showing").innerHTML = "Showing logs before " + end_date;
    } else {
        document.getElementById("dates_showing").innerHTML = "Showing all ";
    }
    
    function expand_message (message_body){
        console.log(this);
    }
    
    if(!(Number((document.getElementById("start_date").value).replaceAll("-", "")) > Number(document.getElementById("end_date").value.replaceAll("-", "")) ) || end_date == 0) {
    
        /**
        *Function that accesses Database via Ajax and builds and appends the table data 
        */
            xhttp.onload = function() {
                try {
                JSON.parse(this.responseText).forEach(function (log) {
                    var subject_line = document.createElement("tr");
                    subject_line.innerHTML = 
                        "<td>" + log.subject + "</td>\n" +
                        "<td>" +log.date_sent + "</td>\n" +
                        "<td>" +log.time_sent + "</td>\n" +
                        "<td>" +log.username + "</td>\n" +
                        "<td>" +log.num_subs + "</td>\n";
                    table.appendChild(subject_line);

                    var message_body = document.createElement("tr");
                    message_body.innerHTML = 
                        '<td colspan="5" class="message_body">' + log.body.substr(0, preview_length).trim() + '<i>...</i>' + '</td>\n';

                    //set data for expanding and shortening the message body
                    message_body.setAttribute("data-body", log.body);
                    message_body.setAttribute("data-prev", 1);

                    //add function to expand and shorten the message text
                    message_body.addEventListener("click", function (){
                        if(this.getAttribute("data-prev") == 1) {
                            this.firstElementChild.innerHTML = this.getAttribute("data-body");
                            this.setAttribute("data-prev", 0);
                        }else {
                            this.firstElementChild.innerHTML = this.getAttribute("data-body").substr(0, preview_length).trim() + '<i>...</i>';
                            this.setAttribute("data-prev", 1);
                        }
                    });

                    table.appendChild(message_body);
                }); } catch (error) {
                    document.getElementById("dates_showing").innerHTML = "Error: encountered unexpected issue with database.";
                }
            }

            xhttp.open("GET", `fetch_logs.php?start_date=${start_date}&end_date=${end_date}`);
            xhttp.send();
    } else {
        document.getElementById("dates_showing").innerHTML = "Error: please enter a start date earlier than the end date.";
    }
}

function init () {
    document.getElementById("search_button").addEventListener("click", create_table);
    document.getElementById("show_all_button").addEventListener("click", function() {
        document.getElementById("start_date").value = "";
        document.getElementById("end_date").value = "";
        create_table();
    });
    create_table();
}

window.addEventListener("load", init);