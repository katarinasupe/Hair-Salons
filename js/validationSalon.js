//ne zelimo stalno provjeravati sva polja pa pamtimo koja ce biti promijenjena
var click = {
    name: 0,
    username: 0,
    address: 0,
    city: 0,
    phone: 0,
    email: 0,
    description: 0,
    shift1: 0,
    shift2: 0,
};

$(document).ready(function() {
    //save (Spremi) i restart(Odustani) gumb zelimo da se ne prikazuju sve dok korisnik ne klikne na gumb Uredi kod podataka
    $("#save").hide();
    $("#restart").hide();
    $(".type").css("text-align", "center");
    $("#error").html("").css("font-size", "11pt").css("color", "red");
    //sakrijemo save i restart gumbe i kod usluga i zaposlenika
    $("button[name = 'saveService']").hide();
    $("button[name = 'resetService']").hide();
    $("button[name = 'saveEmployee']").hide();
    $("button[name = 'resetEmployee']").hide();

    //username nam treba da bi znali u kojem smo salonu pa ga spremimo u sessionStorage radi lakseg dohvacanja
    var username = $("#username").val();
    sessionStorage.setItem("usernameSalon", username);

    //spremimo podatke da mozemo kasnije usporediti
    //ako se nisu promijenili podaci, da ne zovemo fje
    var name = $("#name").val();
    var phone = $("#phone").val();
    var email = $("#email").val();
    var address = $("#address").val();
    var city = $("#city").val();
    var desc = $("#desc").val();

    //ovo moze u css, bitno da ostane prop(disabled, true) ovdje
    //zelimo omoguciti promjene vrijednosti tek kad se klikne na gumb Uredi
    $(".myprofile")
        .css("border", "none")
        .css("outline", "none")
        .css("padding", "5px")
        .css("align-items", "left")
        .css("background-color", "white")
        .prop("disabled", true);

    $(".myservices")
        .css("border", "none")
        .css("outline", "none")
        .css("padding", "5px")
        .css("align-items", "left")
        .css("background-color", "white")
        .css("font-size", "12pt")
        .css("text-align", "center")
        .css("width", "50px")
        .prop("disabled", true);

    $(".myemployees")
        .css("border", "none")
        .css("outline", "none")
        .css("padding", "5px")
        .css("align-items", "left")
        .css("background-color", "white")
        .css("font-size", "12pt")
        .css("text-align", "center")
        .css("width", "120px")
        .prop("disabled", true);

    //klik na Uredi kod podataka - prikazemo Spremi i Odustani i omogućimo mijenjanje vrijednosti
    $("#change").on("click", function() {
        $("#save").show();
        $("#restart").show();
        $(this).hide();
        $(".myprofile")
            .prop("disabled", false)
            .css("border-bottom", "solid lightgray 0.5px");
        $("#email").prop("disabled", true).css("border", "none");
        $("#error").html("");
    });

    //klik na Odustani kod podataka
    $("#restart").on("click", function() {
        $(this).hide();
        $("#save").hide();
        $("#change").show();
        $(".myprofile").prop("disabled", true).css("border", "none");
        $("#error").html("");

        click = {
            name: 0,
            username: 0,
            address: 0,
            city: 0,
            phone: 0,
            email: 0,
            description: 0,
            shift1: 0,
            shift2: 0,
        };
    });

    //promjena Imena salona kod podataka; reagiramo na svaku promjenu i validiramo unos
    $("#name").bind("propertychange change click keyup input paste", function() {
        click["name"] = 1;
        var n = $(this).val();
        if (n !== name) {
            if (!validateName()) {
                $("#name").css("border-bottom", "solid red 0.5px");
            } else {
                $("#name").css("border-bottom", "solid lightgray 0.5px");
            }
        } else {
            $("#name").css("border-bottom", "solid lightgray 0.5px");
        }
    });

    //promjena Korisničkog imena i pozivanje validacije nove vrijednosti
    $("#username").bind(
        "propertychange change click keyup input paste",
        function() {
            click["username"] = 1;
            $("#error").html("");
            var u = $(this).val();

            if (u !== username) {
                if (!validateUsername()) {
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

    //promjena Broja telefona + pozivanje validacije
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

    //promjena Adrese + pozivanje validacije
    $("#address").bind(
        "propertychange change click keyup input paste",
        function() {
            click["address"] = 1;
            var a = $(this).val();
            if (a !== address) {
                if (!validateAddress()) {
                    $("#address").css("border-bottom", "solid red 0.5px");
                } else {
                    $("#address").css("border-bottom", "solid lightgray 0.5px");
                }
            } else {
                $("#address").css("border-bottom", "solid lightgray 0.5px");
            }
        }
    );

    //promjena Grada + pozivanje validacije
    $("#city").bind("propertychange change click keyup input paste", function() {
        click["city"] = 1;
        var a = $(this).val();
        if (a !== city) {
            if (!validateCity()) {
                $("#city").css("border-bottom", "solid red 0.5px");
            } else {
                $("#city").css("border-bottom", "solid lightgray 0.5px");
            }
        } else {
            $("#city").css("border-bottom", "solid lightgray 0.5px");
        }
    });

    //promjena Opisa + pozivanje validacije
    $("#desc").bind("propertychange change click keyup input paste", function() {
        click["description"] = 1;
        if (!validateDescription()) {
            $("#desc").css("border-bottom", "solid red 0.5px");
            var greska = "Predugačak opis.";
            $("#error").html(greska);
        } else {
            $("#desc").css("border-bottom", "solid lightgray 0.5px");
            $("#error").html("");
        }
    });

    //promjene Radnog vremena (s1 - shift1 - begin/end, s2 - shift2 - begin/end)
    $("#s1b").bind("propertychange change click keyup input paste", function() {
        click["shift1"] = 1;
        var vr = $("input[name='s1']").val();
        //console.log(vr);
        if (!validateShift1()) {
            $("#s1b").css("border-bottom", "solid red 0.5px");
            $("#s1e").css("border-bottom", "solid red 0.5px");
            var greska =
                'Vrijeme mora biti u formatu hh:mm (npr. 08:00). Ako ne želite unijeti vrijeme, ostavite prazno ili upišite znak "-".';
            $("#error").html(greska);
        } else {
            $("#s1b").css("border-bottom", "solid lightgray 0.5px");
            $("#s1e").css("border-bottom", "solid lightgray 0.5px");
            $("#error").html("");
        }
    });

    $("#s1e").bind("propertychange change click keyup input paste", function() {
        click["shift1"] = 1;
        var vr = $("input[name='s1']").val();
        //console.log(vr);
        if (!validateShift1()) {
            $("#s1b").css("border-bottom", "solid red 0.5px");
            $("#s1e").css("border-bottom", "solid red 0.5px");
            var greska =
                'Vrijeme mora biti u formatu hh:mm (npr. 08:00). Ako ne želite unijeti vrijeme, ostavite prazno ili upišite znak "-".';
            $("#error").html(greska);
        } else {
            $("#s1b").css("border-bottom", "solid lightgray 0.5px");
            $("#s1e").css("border-bottom", "solid lightgray 0.5px");
            $("#error").html("");
        }
    });

    $("#s2b").bind("propertychange change click keyup input paste", function() {
        click["shift2"] = 1;
        if (!validateShift2()) {
            $("#s2b").css("border-bottom", "solid red 0.5px");
            $("#s2e").css("border-bottom", "solid red 0.5px");
            var greska =
                'Vrijeme mora biti u formatu hh:mm (npr. 08:00). Ako ne želite unijeti vrijeme, ostavite prazno ili upišite znak "-".';
            $("#error").html(greska);
        } else {
            $("#s2b").css("border-bottom", "solid lightgray 0.5px");
            $("#s2e").css("border-bottom", "solid lightgray 0.5px");
            $("#error").html("");
        }
    });

    $("#s2e").bind("propertychange change click keyup input paste", function() {
        click["shift2"] = 1;
        if (!validateShift2()) {
            $("#s2b").css("border-bottom", "solid red 0.5px");
            $("#s2e").css("border-bottom", "solid red 0.5px");
            var greska =
                'Vrijeme mora biti u formatu hh:mm (npr. 08:00). Ako ne želite unijeti vrijeme, ostavite prazno ili upišite znak "-".';
            $("#error").html(greska);
        } else {
            $("#s2b").css("border-bottom", "solid lightgray 0.5px");
            $("#s2e").css("border-bottom", "solid lightgray 0.5px");
            $("#error").html("");
        }
    });

    //klik na gumb koji sluzi za preusmjeravanje na prikaz stranice salona
    $("#mypage").on("click", function() {
        var idSalon = $(this).val();
        window.location.replace("?rt=salons/show&hair_salon_id=" + idSalon);
    });

    //klik na gumb za brisanje rezervacije - id rezervacije citamo iz id-a gumba
    $("button[name = 'removeAppointment']").on("click", function() {
        var id = $(this).prop("id");
        //console.log(id);
        removeAppointment(id);
    });

    //klik na gumb za brisanje usluge
    $("button[name = 'removeService']").on("click", function() {
        var serviceId = $(this).prop("id");
        //console.log(serviceId);
        var salonUsername = sessionStorage.getItem("usernameSalon");
        removeService(serviceId, salonUsername);
    });

    //klik na gumb za brisanje zaposlenika
    $("button[name = 'removeEmployee']").on("click", function() {
        var employeeId = $(this).prop("id");
        var salonUsername = sessionStorage.getItem("usernameSalon");
        removeEmployee(employeeId, salonUsername);
    });

    //uređivanje usluge
    $("button[name = 'editService']").on("click", function() {
        //dohvatimo id usluge i tocno taj redak u tablici (id retka je id usluge) te omogucimo promjene
        //prikaz, omogucavanje uređivanja i sve ostalo slicno kao i kod podataka
        var id = $(this).prop("id");

        var btns = $("#" + id + " button");
        //console.log(btns);
        var btn_edit = $(btns[0]);
        var btn_save = $(btns[1]);
        var btn_remove = $(btns[2]);
        var btn_reset = $(btns[3]);

        $(this).hide();
        btn_remove.hide();
        btn_save.show();
        btn_reset.show();

        var row = $("#" + id + " input");
        //console.log(row);

        var duration = $(row[0]);
        var oldDuration = duration.val();
        duration.prop("disabled", false).css("border", "solid 1px lightgray");
        //console.log("Trajanje" + duration.val());

        var price = $(row[1]);
        var oldPrice = price.val();
        price.prop("disabled", false).css("border", "solid 1px lightgray");
        //console.log("Cijena" + price.val());

        var discount = $(row[2]);
        var oldDiscount = discount.val();
        discount.prop("disabled", false).css("border", "solid 1px lightgray");
        //console.log("Popust" + discount.val());

        //promjena trajanja + validacija
        duration.bind("propertychange change click keyup input paste", function() {
            var dur = duration.val();
            if (!validateDuration(dur)) {
                duration.css("border", "solid 1px red");
            } else {
                duration.css("border", "solid 1px lightgray");
            }
        });

        //promjena cijene + validacija
        price.bind("propertychange change click keyup input paste", function() {
            var valp = price.val();
            if (!validatePrice(valp)) {
                price.css("border", "solid 1px red");
            } else {
                price.css("border", "solid 1px lightgray");
            }
        });

        //promjena popusta + validacija
        discount.bind("propertychange change click keyup input paste", function() {
            var disc = discount.val();
            if (!validateDiscount(disc)) {
                discount.css("border", "solid 1px red");
            } else {
                discount.css("border", "solid 1px lightgray");
            }
        });

        //klik na Odustani - vratimo na staro i onemogucimo promjene
        btn_reset.on("click", function() {
            btn_edit.show();
            btn_remove.show();
            btn_reset.hide();
            btn_save.hide();

            duration.prop("disabled", true).css("border", "none").val(oldDuration);

            price.prop("disabled", true).css("border", "none").val(oldPrice);

            discount.prop("disabled", true).css("border", "none").val(oldDiscount);
        });

        //klik na spremi - provjerimo jesu li unesene vrijednosti ok i ako jesu, zovemo fju koja ih sprema
        btn_save.on("click", function() {
            //console.log(btn_save.prop('id'));
            var newDuration = duration.val();
            var newPrice = price.val();
            var newDiscount = discount.val();

            if (
                validateDuration(newDuration) &&
                validatePrice(newPrice) &&
                validateDiscount(newDiscount)
            ) {
                var newDur = Number(newDuration) / 15;
                var newDisc = Number(newDiscount) / 100;
                updateService(id, newDur, newPrice, newDisc);
            }
        });
    });

    //uređivanje zaposlenika - vrlo slicno promjeni usluge
    $("button[name ='editEmployee']").on("click", function() {
        var id = $(this).prop("id");

        var Btns = $("#-" + id + " button");
        console.log(Btns);
        var btn_edit = $(Btns[0]);
        console.log(btn_edit.prop("name"));
        var btn_save = $(Btns[1]);
        var btn_remove = $(Btns[2]);
        var btn_reset = $(Btns[3]);

        $(this).hide();
        btn_remove.hide();
        btn_save.show();
        btn_reset.show();

        var row = $("#-" + id + " input");

        var name = $(row[0]);
        var oldName = name.val();
        name.prop("disabled", false).css("border", "solid 1px lightgray");
        //console.log("Ime" + name.val());

        var shift = $(row[1]);
        var oldShift = shift.val();
        shift.prop("disabled", false).css("border", "solid 1px lightgray");
        //console.log("Smjena" + shift.val());

        name.bind("propertychange change click keyup input paste", function() {
            var name1 = name.val();
            if (!validateEmployeeName(name1)) {
                name.css("border", "solid 1px red");
            } else {
                name.css("border", "solid 1px lightgray");
            }
        });

        shift.bind("propertychange change click keyup input paste", function() {
            var shiftv = shift.val();
            if (!validateShift(shiftv)) {
                shift.css("border", "solid 1px red");
            } else {
                shift.css("border", "solid 1px lightgray");
            }
        });

        btn_reset.on("click", function() {
            btn_edit.show();
            btn_remove.show();
            btn_reset.hide();
            btn_save.hide();

            name.prop("disabled", true).css("border", "none").val(oldName);

            shift.prop("disabled", true).css("border", "none").val(oldShift);
        });

        btn_save.on("click", function() {
            //console.log(btn_save.prop('id'));
            var newName = name.val();
            var newShift = shift.val();

            if (validateEmployeeName(newName) && validateShift(newShift)) {
                updateEmployee(id, newName, newShift);
            }
        });
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

//funkcije za validaciju podataka (provjere duljine, znakova itd.)
function validateName() {
    var name1 = $("#name").val();
    var regexName = /[A-Zščćđž\s-.0-9]/gi;
    var find = name1.match(regexName);
    if (name1.length === 0) {
        //console.log("ovdje sam");
        return false;
    } else if (name1.length < 3 || name1.length > 45) {
        return false;
    } else if (find.length !== name1.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }
    return true;
}

function validateUsername() {
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

    checkSalonUsernameInBase();
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

function validateAddress() {
    var adr = $("#address").val();
    var regexAdr = /[A-Zščćđž\s-.,0-9]/gi;
    var find = adr.match(regexAdr);

    if (adr.length < 3 || adr.length > 95) {
        return false;
    } else if (find.length !== adr.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }
    return true;
}

function validateCity() {
    var cty = $("#city").val();
    var regexCity = /[A-Zščćđž\s-]/gi;
    var find = cty.match(regexCity);

    if (cty.length < 2 || cty.length > 25) {
        return false;
    } else if (find.length !== cty.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }
    return true;
}

function validateShift1() {
    var begin = $("#s1b").val();
    var end = $("#s1e").val();

    if (
        (begin.length === 5 || begin.length === 0 || begin.length === 1) &&
        (end.length === 5 || end.length === 0 || end.length === 1)
    ) {
        if (begin !== "" && begin !== "-" && end !== "" && end !== "-") {
            var timeb = begin.split(":");
            var timee = end.split(":");

            if (timeb[0] >= "00" && timeb[0] <= "23") {
                if (timeb[1] >= "00" && timeb[1] <= "59") {
                    if (timee[0] >= "00" && timee[0] <= "23") {
                        if (timee[1] >= "00" && timee[1] <= "59") {
                            if (timeb[0] < timee[0]) {
                                return true;
                            } else if (timeb[0] === timee[0]) {
                                if (timeb[1] <= timee[1]) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        } else if ((begin === "" || begin === "-") && (end === "" || end === "-")) {
            return true;
        }
    }
    return false;
}

function validateShift2() {
    var begin = $("#s2b").val();
    var end = $("#s2e").val();

    if (
        (begin.length === 5 || begin.length === 0 || begin.length === 1) &&
        (end.length === 5 || end.length === 0 || end.length === 1)
    ) {
        if (begin !== "" && begin !== "-" && end !== "" && end !== "-") {
            var timeb = begin.split(":");
            var timee = end.split(":");

            if (timeb[0] >= "00" && timeb[0] <= "23") {
                if (timeb[1] >= "00" && timeb[1] <= "59") {
                    if (timee[0] >= "00" && timee[0] <= "23") {
                        if (timee[1] >= "00" && timee[1] <= "59") {
                            if (timeb[0] < timee[0]) {
                                return true;
                            } else if (timeb[0] === timee[0]) {
                                if (timeb[1] <= timee[1]) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        } else if ((begin === "" || begin === "-") && (end === "" || end === "-")) {
            return true;
        }
    }
    return false;
}

function validateDescription() {
    var desc = $("#desc").val();

    if (desc.length > 250) return false;
    return true;
}

function validateSalonForm() {
    //fja se poziva klikom na submit button u formi za promjenu podataka salona
    var newName = document.forms["form-salon"]["name"].value;

    if (click["name"]) {
        if (newName.length === 0) {
            return false;
        }

        if (!validateName()) {
            return false;
        }
    }

    if (click["username"]) {
        if (!validateUsername()) {
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
    if (click["address"]) {
        if (!validateAddress()) {
            return false;
        }
    }
    if (click["city"]) {
        if (!validateCity()) {
            return false;
        }
    }
    if (click["shift1"]) {
        if (!validateShift1()) {
            return false;
        }
    }
    if (click["shift2"]) {
        if (!validateShift2()) {
            return false;
        }
    }

    if (click["description"]) {
        if (!validateDescription()) {
            return false;
        }
    }
    return true;
}

function validateDuration(duration) {
    var val = Number(duration);
    if (val > 0 && val % 15 === 0) {
        return true;
    }
    return false;
}

function validatePrice(price) {
    var isnum = /^\d+$/.test(price);
    return isnum;
}

function validateDiscount(discount) {
    var val = Number(discount);
    if (val >= 0 && val <= 100) {
        return true;
    }
    return false;
}

function validateShift(shift) {
    var shiftNo = Number(shift);
    if (shiftNo === 1 || shiftNo === 2) {
        return true;
    }
    return false;
}

function validateEmployeeName(name) {
    var regexName = /[A-Zščćđž\s]/gi;
    var find = name.match(regexName);
    if (name.length === 0) {
        //console.log("ovdje sam");
        return false;
    } else if (name.length < 3 || name.length > 45) {
        return false;
    } else if (find.length !== name.length) {
        //postoji znak koji se ne poklapa s regularnim izrazom
        return false;
    }
    return true;
}

//fje s ajax pozivima

//provjera postoji li username u bazi
function checkSalonUsernameInBase() {
    var user = $("#username").val();
    var username = sessionStorage.getItem("usernameSalon");

    $("#error").html("");

    if (user !== username) {
        $.ajax({
            url: "index.php?rt=ajax/checkSalonUsernameInBase",
            data: {
                username: user,
            },
            type: "post",
            datatype: "json",
            success: function(data) {
                if (data.hasOwnProperty("found")) {
                    $("#error").html(data.found);
                    console.log(data.found);
                    $("#username").css("border-bottom", "solid red 0.5px");
                    $("#save").prop("disabled", true);
                } else if (data.hasOwnProperty("error")) {
                    $("#error").html(data.error);
                    console.log(data.error);
                } else if (data.hasOwnProperty("free")) {
                    $("#error").html("");
                    console.log(data.free);
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
            deleting: "salon",
            appointment_id: id,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=salons/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}

//brisanje usluge
function removeService(serviceId, usernameSalon) {
    $.ajax({
        url: "index.php?rt=ajax/removeServiceInSalon",
        data: {
            service_id: serviceId,
            salon_username: usernameSalon,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=salons/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}

//brisanje zaposlenika
function removeEmployee(employeeId, salonUsername) {
    $.ajax({
        url: "index.php?rt=ajax/removeEmployeeFromSalon",
        data: {
            employee_id: employeeId,
            salon_username: salonUsername,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=salons/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}

//promjena usluge
function updateService(id, newDuration, newPrice, newDiscount) {
    var username = sessionStorage.getItem("usernameSalon");
    $.ajax({
        url: "index.php?rt=ajax/updateServiceInSalon",
        data: {
            service_id: id,
            salon_username: username,
            duration: newDuration,
            price: newPrice,
            discount: newDiscount,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=salons/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}

//promjena zaposlenika
function updateEmployee(employee_id, newName, newShift) {
    $.ajax({
        url: "index.php?rt=ajax/updateEmployee",
        data: {
            employee_id: employee_id,
            name: newName,
            shift: newShift,
        },
        type: "post",
        datatype: "json",
        success: function(data) {
            if (data.hasOwnProperty("done")) {
                window.location.replace("index.php?rt=salons/myprofile");
            } else if (data.hasOwnProperty("error")) {
                console.log(data.error);
            }
        },
        error: function() {
            console.log("Greska u ajax pozivu.");
        },
    });
}
