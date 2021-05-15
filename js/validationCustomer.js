//ne zelimo stalno provjeravati sva polja pa pamtimo koja ce biti promijenjena
var click = {
    name: 0,
    username: 0,
    phone: 0,
    email: 0,
};

$(document).ready(function() {
    //save zelimo tek kad korisnik klikne na promjeni
    $("#save").hide();
    $("#restart").hide();
    $(".type").css("text-align", "center");
    $("#error").html("");

    //spremimo podatke da mozemo kasnije usporediti
    //ako se nisu promijenili podaci, da ne zovemo fje
    //username spremamo u sessionStorage radi lakseg dohvacanja
    var username = $("#username").val();
    sessionStorage.setItem("username", username);
    var name = $("#name").val();
    var phone = $("#phone").val();
    var email = $("#email").val();

    //ovo moze u css, bitno da ostane prop(disabled, true) ovdje
    $(".myprofile")
        .css("border", "none")
        .css("outline", "none")
        .css("padding", "3px")
        .css("align-items", "left")
        .css("background-color", "white")
        .prop("disabled", true);

    //klik na Uredi kod podataka - omogucimo izmjene i prikazemo gumbe Spremi i Odustani
    $("#change").on("click", function() {
        $("#save").show();
        $("#restart").show();
        $(this).hide();
        $(".myprofile")
            .prop("disabled", false)
            .css("border-bottom", "solid lightgray 0.5px");
        //iduce vrijednosti ne zelimo mijenjati
        $("#email").prop("disabled", true).css("border", "none");
        $("#sex").prop("disabled", true).css("border", "none");
        $("#date_of_birth").prop("disabled", true).css("border", "none");
        $("#error").html("");
    });

    //klik na Odustani - vraca sve na staro
    $("#restart").on("click", function() {
        $(this).hide();
        $("#save").hide();
        $("#change").show();
        $(".myprofile").prop("disabled", true).css("border", "none");
        $("#error").html("");

        click = {
            name: 0,
            username: 0,
            phone: 0,
            email: 0,
        };

        //console.log(click);
    });

    //promjene Imena i prezime i validacija istog
    //ukoliko nova vrijednost ne prolazi validaciju, crta ispod se zacrveni
    //prethodno navedeno vrijedi za sve vrijednosti koje se mijenjaju i ovdje i kod prikaza profila za salon
    $("#name").bind("propertychange change click keyup input paste", function() {
        click["name"] = 1;
        var n = $(this).val();
        if (n !== name) {
            if (!validateCustomerName()) {
                $("#name").css("border-bottom", "solid red 0.5px");
            } else {
                $("#name").css("border-bottom", "solid lightgray 0.5px");
            }
        } else {
            $("#name").css("border-bottom", "solid lightgray 0.5px");
        }
    });

    //promjena Korisničkog imena i pozivanje validacije
    $("#username").bind(
        "propertychange change click keyup input paste",
        function() {
            click["username"] = 1;
            $("#error").html("");
            var u = $(this).val();

            if (u !== username) {
                if (!validateCustomerUsername()) {
                    $("#username").css("border-bottom", "solid red 0.5px");
                    $("#save").prop("disabled", true);
                } else {
                    $("#username").css("border-bottom", "solid lightgray 0.5px");
                    $("#error").html("");
                    $("#save").prop("disabled", false);
                }
            } else {
                $("#username").css("border-bottom", "solid lightgray 0.5px");
                $("#error").html("");
                $("#save").prop("disabled", false);
            }
        }
    );

    //promjena Broja telefona i pozivanje validacije
    $("#phone").bind(
        "propertychange change click keyup input paste",
        function() {
            click["phone"] = 1;
            var p = $(this).val();
            if (p !== phone) {
                if (!validatePhoneNumber()) {
                    $("#phone").css("border-bottom", "solid red 0.5px");
                } else {
                    $("#phone").css("border-bottom", "solid lightgray 0.5px");
                }
            } else {
                $("#phone").css("border-bottom", "solid lightgray 0.5px");
            }
        }
    );

    //brisanje rezervacije
    $("button[name = 'removeAppointment']").on("click", function() {
        var id = $(this).prop("id");
        console.log(id);
        removeAppointment(id);
    });

    /* $("#email").change(function() {
            click['email'] = 1;
            var mail = $(this).val();
            if(mail !== email){
                if(!validateMail()){
                    $("#email").css('border-bottom', 'solid red 0.5px');
                }else{
                    $("#email").css('border-bottom', 'solid lightgray 0.5px');
                }
            }
            else{
                $("#email").css('border-bottom', 'solid lightgray 0.5px');
            }
        }); */
});

//fje koje nam služe za validaciju novih podataka (duljina, znakovi,...)
function validateCustomerName() {
    var name1 = $("#name").val();
    var regexName = /[A-Zščćđž\s-]/gi;
    var find = name1.match(regexName);
    if (name1.length === 0) {
        //console.log("ovdje sam");
        return false;
    } else if (name1.length < 3 || name1.length > 40) {
        return false;
    } else if (find.length !== name1.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }
    return true;
}

function validateCustomerUsername() {
    var usern = $("#username").val();
    var regexName = /[A-Z0-9]/gi;
    var find = usern.match(regexName);
    var found = false;

    if (usern === "") {
        return false;
    } else if (usern.length < 3 || usern.length > 20) {
        return false;
    } else if (find.length !== usern.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }

    checkCustomerUsernameInBase();
    return true;
}

function validatePhoneNumber() {
    var phoneNum = $("#phone").val();
    var regexPhone = /\d/g;
    var find = phoneNum.match(regexPhone);

    if (phoneNum.length < 8 || phoneNum.length > 15) {
        return false;
    } else if (find.length !== phoneNum.length) {
        return false;
    }
    return true;
}

/* 
function validateMail(){

    var mail = $("#email").val();
    var regexMail =  /[a-zA-Z0-9_\\.\\+-]+@[a-zA-Z0-9-]+\\.[a-zA-Z0-9-\\.]+"/;
    var find = mail.match(regexMail);
    console.log(mail + " " + find)

    if(find.length !== mail.length){
        return false;
    }
} */

function validateCustomerForm() {
    //poziva se prije slanja podataka formi
    //ako validacija podataka nije prosla, podaci se ne salju skripti koja ih obrađuje
    var newName = document.forms["form-customer"]["name"].value;

    if (click["name"]) {
        if (newName.length === 0) {
            return false;
        }

        if (!validateCustomerName()) {
            return false;
        }
    }

    if (click["username"]) {
        if (!validateCustomerUsername()) {
            return false;
        }
    }
    if (click["phone"]) {
        if (!validatePhoneNumber()) {
            return false;
        }
    }
    if (click["email"]) {
        if (!validateMail()) {
            return false;
        }
    }

    return true;
}

//fje s ajax pozivima za promjene podataka

//provjera postoji li korisnicko ime u bazi
function checkCustomerUsernameInBase() {
    var user = $("#username").val();
    var username = sessionStorage.getItem("username");

    $("#error").html("");

    if (user !== username) {
        $.ajax({
            url: "index.php?rt=ajax/checkCustomerUsernameInBase",
            data: {
                username: user,
            },
            type: "post",
            datatype: "json",
            success: function(data) {
                if (data.hasOwnProperty("found")) {
                    $("#error").html(data.found);
                    $("#username").css("border-bottom", "solid red 0.5px");
                    $("#save").prop("disabled", true);
                } else if (data.hasOwnProperty("error")) {
                    $("#error").html(data.error);
                } else if (data.hasOwnProperty("free")) {
                    $("#error").html("");
                    $("#username").css("border-bottom", "solid lightgray 0.5px");
                    $("#save").prop("disabled", false);
                }
            },
            error: function() {
                console.log("Greska u ajax pozivu.");
            },
        });
    } else {
        $("#save").prop("disabled", false);
    }
}

//brisanje rezervacije
function removeAppointment(id) {
    $.ajax({
        url: "index.php?rt=ajax/removeAppointmentWithId",
        data: {
            deleting: "korisnik",
            appointment_id: id,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=customer/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}