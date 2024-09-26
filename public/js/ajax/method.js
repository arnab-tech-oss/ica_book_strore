// crud start
function ajaxCall(form_id, url_name, target_id, method = "POST") {
    // getting the all from form
    var form = document.getElementById(form_id);
    // setting all input into the forData object
    var formdata = new FormData(form);
    var formElements_button = Array.from(form.elements).pop();
    // getting the button of the form and passing into the preloader function
    preloader(formElements_button);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(target_id).innerHTML = this.responseText;
            stopPreloader(formElements_button, "span");
        }
    };
    xhttp.open(method, url_name, true);
    xhttp.send(formdata);
}

function editForm(url_name, table_id, target_id, method = "POST") {
    preloader('', target_id);
    // getting the button of the form and passing into the preloader function
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(target_id).innerHTML = this.responseText;
            stopPreloader('', "span", target_id);
        }
    };
    xhttp.open(method, url_name + "?id=" + table_id, true);
    xhttp.send();
}


function deleteForm(url_name, table_id, target_id, method = "POST") {
    if (confirm('Are sure to delete !!!')) {
        preloader('', target_id);
        // getting the button of the form and passing into the preloader function
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(target_id).innerHTML = this.responseText;
                stopPreloader('', "span", target_id);
            }
        };
        xhttp.open(method, url_name + "?id=" + table_id, true);
        xhttp.send();
    }
}


function fetchApi(form_id, url_name, target_id, method = "POST") {
    const form = document.getElementById(form_id);
    // setting all input into the forData object
    FormData = new FormData(form);
    const formElements_button = Array.from(form.elements).pop();
    // getting the button of the form and passing into the preloader function
    preloader(formElements_button);
    fetch(url_name, {
        method: method,
        body: FormData,
    })
        .then((response) => response.text())
        .then((text) => {
            document.getElementById(target_id).innerHTML = text;
            stopPreloader(formElements_button, "span");
        })
        .catch((error) => console.error("Error:", error));
}

fetch("test.php", {})
    .then(function (data) {
        console.log(data);
    })
    .catch((error) => console.error("Error:", error));
// crud end

// some special fucntion start
function preloader(element_data, id = "") {
    var element = "";
    if (id != "") {
        element = document.getElementById(id);
    } else {
        element = element_data;
    }

    element.disabled = true;
    createdd_element = createMenuItem("span", {
        name: "",
        class: "spinner-border spinner-border-sm",
        id: "lol",
        size: "20px",
    });
    element.appendChild(createdd_element);
}

function stopPreloader(element_data, child, id = "") {
    var element = "";
    if (id != "") {
        element = document.getElementById(id);
    } else {
        element = element_data;
    }
    element.removeChild(element.firstElementChild);
    element.disabled = false;
}

function createMenuItem(element, data) {
    let created_element = document.createElement(element);
    created_element.textContent = data.name;
    created_element.setAttribute("class", data.class);
    created_element.setAttribute("id", data.id);
    created_element.setAttribute("style", "font-size:" + data.size);
    return created_element;
}
// some special function end


