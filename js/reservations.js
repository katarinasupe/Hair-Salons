 $(document).ready(function(){  
//------------------------postavljanje varijabli------------------------
    var picked_services_id=[],picked_services_name=[]; //spremamo id-eve i imena odabranih usluga1

    var duration=0,total_price=0,discount=0,discount_price=0; //ukupno trajanje i cijena

    var picked_date=null,clicked_time=null; //odabrani datum i vrijeme

    var start=0,end=0,start_shift2=0,end_shift2=0; //početak i kraj za petlju koja provjerava termine

    //računamo današnji datum
    var today = new Date();
    var hh_now=today.getHours();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); 
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    picked_date=today;//postavljamo odabrani datum na današnji

    var employee_id=0;//id zaposlenika, na početku postavljamo na nula(tj. zaposlenik nije odabran)
    
    var tmp_shift2=shift2;//pamtimo postoji li druga smjena u drugoj varijabli jer ćemo shift2 mijenjati ovisno o odabranom zaposleniku
    var shift1_until,shift2_until;//kraj radnog vremena

    var reserve_clicked=0;

//----------------------------------------------------------------
    //klik na gumb rezerviraj(zajednički dio za onaj na stranici i onaj koji se pojavi u modalu kada stisnemo dodaj novu uslugu)
    //dodaje se usluga i ažuriraju podaci
    function reserve_on_click(clicked_button_id) {
        //clicked_button_id je service_id one usluge čiji je gumb stisnut, spremamo taj id u polje
        picked_services_id.push(clicked_button_id);

        //ažuriranje podataka......tražimo u polju services onu uslugu kojoj pripada clicked_button_id te ažuriramo podatke
        for(var i=0; i<services.length; ++i){
            if(Number(services[i].service_id) === clicked_button_id){               
                    picked_services_name.push(services[i].name);

                    total_price+=Number(services[i].price);
                    discount+=Number(services[i].price)*Number(services[i].discount);
                    discount_price=total_price-discount;

                    duration+=Number(services[i].duration);
                    end=end-Number(services[i].duration)*15; //oduzimamo od kraja onoliko minuta koliko traje usluga (duration*15)  
                    if(shift2===1){
                        end_shift2=end_shift2-Number(services[i].duration)*15;
                    }                           
                }
            }
   
            //dodajemo div s imenom odabrane usluge
            var div=$("<div>");
            div.attr("class","picked_service");
            div.attr("id","service_" + picked_services_id[picked_services_id.length-1]);//dodajemo zadnju dodanu u polje
            div.html(picked_services_name[picked_services_id.length-1]);

            //dodajemo gumb za maknuti uslugu
            var x=$("<button>");
            x.attr("class","close-div");
            x.html("X");
            div.append(x);
            $("#chosen-services").append(div);

            $( "#price" ).html("Cijena: " + total_price + "kn");//ažuriramo ispis cijene
            $( "#discount" ).html("Popust: " + discount + "kn");//ažuriramo ispis popusta
            $( "#discount-price" ).html("Cijena s popustom: " + discount_price + "kn");//ažuriramo ispis cijene

            //ažuriramo ispis trajanja
            var dur_h=Math.floor(duration*15/60);
            var dur_m=Math.floor((duration*15/60-dur_h)*60);
            if(dur_h===0 && dur_m===0)
                dur="0min";
            else if(dur_h===0 && dur_m!==0)
                dur=dur_m+"min";
            else if(dur_h!==0 && dur_m===0)
                dur=dur_h+"h";
            else    
                dur=dur_h+":"+dur_m+"h";

            $( "#duration" ).html("Trajanje: " + dur);

            $( "#datepicker" ).val(picked_date); 
            $( "#datepicker" ).trigger("change"); // ručno triggeramo datepicker da se ispišu novi termini
    }

    //klik na gumb za micanje usluge
    //miče se usluga i ažuriraju podaci, ukoliko se makne zadnja usluga, zatvara se modal za rezervaciju
    $("body").on("click", ".close-div", function(){
        var id = $(this).parent().attr("id").slice(8);//jer je id npr. service_1 pa nam treba samo nakon osmog charactera. može i split('_')
        id=Number(id);
        //tražimo podatke od usluge koja je maknuta u popisu usluga da bi ažurirali podatke
        for(var i=0; i<services.length; ++i){         
            if(Number(services[i].service_id) === id){ 
                for(var j=0; j<picked_services_id.length; ++j) 
                    if( picked_services_id[j]==id){
                        picked_services_name.splice(j,1);//mičemo tu uslugu iz polja
                        picked_services_id.splice(j,1);
                        break;
                    }
                total_price-=Number(services[i].price);//smanjujemo cijenu
                discount-=Number(services[i].price)*Number(services[i].discount);
                discount_price=total_price-discount;

                duration= duration - Number(services[i].duration);//smanjujemo trajanje
                end=end+Number(services[i].duration)*15; //povečavamo kraj
                if(shift2===1){
                        end_shift2=end_shift2+Number(services[i].duration)*15;
                }              
            }
        } 

        //ažuriramo ispis    
        $( "#price" ).html("Cijena: " + total_price + "kn");
        $( "#discount" ).html("Popust: " + discount + "kn");
        $( "#discount-price" ).html("Cijena s popustom: " + discount_price + "kn");//ažuriramo ispis cijene

        var dur_h=Math.floor(duration*15/60);
        var dur_m=Math.floor((duration*15/60-dur_h)*60);
        if(dur_h===0 && dur_m===0)
            dur="0min";
        else if(dur_h===0 && dur_m!==0)
            dur=dur_m+"min";
        else if(dur_h!==0 && dur_m===0)
            dur=dur_h+"h";
        else    
            dur=dur_h+":"+dur_m+"h";

        $( "#duration" ).html("Trajanje: " + dur);

        $( "#datepicker" ).trigger("change");
       
        $(this).parent().remove(); //brišemo pripadni div

        //ako smo izbrisali posljednju uslugu zatvaramo modal
        if(picked_services_id.length===0){
            $("#close2").trigger("click");
        }
    });

    //datepicker-postavljamo da je minimalni datum koji se može odabrati današnji, a maksimalni 4 mjeseca od danas
    $( function() {
        $( "#datepicker" ).datepicker({ minDate: 0, maxDate: "4M" });
    } );

    //klik na gumb za zatvaranje modala za rezervaciju. Resetiramo stvari i skrivamo modal
    $("#close2").on("click", function() {
        $("#modal2").css("display", "none");
        $("#free-appointments").empty();
        $("#service-container").empty();
        $("#chosen-services").empty();
        $( "#datepicker" ).val("");
        picked_services_id=[];
        picked_services_name=[];
        duration=0;
        start=0;
        end=0;
        if(shift2===1){
            end_shift2=0;
            start_shift2=0;
        } 
        picked_date=today;
        total_price=0;
        discount=0;
        clicked_time=null;
        $( "#employees" ).val("svejedno");
        employee_id=0;
        shift1=1;
        shift2=tmp_shift2;
        $("#occupied").css("display","none");
    });
    
    
    //reagiranje na click na gumb rezerviraj (onaj na početnoj stranici)
    $(".book-btn").on("click", function() {
        
        //0-nitko nije ulogiran-traži da se ulogira, 'salon'-ulogiran je salon-reci da ne može, inače-ulogiran je korisnik-otvori modal za rezervaciju i pozovi funkciju
        if( customer_id>0){ 
            $("#service-container").empty();
            $("#modal2").css("display", "block");//otkrij modal
            
            //vremena pretvaramo u minute te ih tako uspoređujemo
            //računamo početno i krajnje vrijeme (početak i kraj petlje) 
            start=Number(hours_start)*60+Number(minutes_start); 
            shift1_until=end=Number(hours_end)*60+Number(minutes_end);
            if(shift2===1){
                start_shift2=Number(hours_start_shift2)*60+Number(minutes_start_shift2); 
                shift2_until=end_shift2=Number(hours_end_shift2)*60+Number(minutes_end_shift2);
            }
            //pozovi funkciju za ažuriranje podataka
            reserve_on_click( Number($(this).attr("id")) );  
        }
        else if( customer_id==-1){
                
                $("#modal4").css("display", "block");//otkrij modal
        }
        else{
            //pamtimo na što se kliklo ukoliko nitko nije prijavljen da bi nakon prijave mogli otvoriti odgovarajući modal
            if(sessionStorage.getItem("btn_id")==null){
                sessionStorage.setItem("btn_id",Number($(this).attr("id")));
                $("#login").trigger("click");    
            }
        }      
    });

    //reagiranje na odabir zaposlenika
    $( "#employees" ).on("change", function(){ 
        if($( "#employees" ).val()!="svejedno"){
            var chld=$( "#employees" ).children();
            for(var i=0;i<chld.length;++i){
                //id svake opcije u select je (employee_id)_(employee_shift), a u html-u je ime zaposlenika 
                //(Napomena: smatramo da su imena različita jer kako bi ih inaće razlikovali u selectu)
                if($( "#employees" ).val()===chld.eq(i).html()){
                    [employee_id,employee_shift]=chld.eq(i).attr("id").split('_');
                    break;
                }
            }
            //ako je zaposlenik iz prve smjene, onda ćemo provjeriti samo termine iz prve smjene
            if(employee_shift==1){
                shift1=1;
                shift2=0;   
            }
            //isto za drugu
            else if(employee_shift==2){
                shift1=0;
                shift2=1;   
            }
        }
        //ako je odabrana opcija svejedno, postavljamo employee_id na 0 i shift1, shift2 na inicijalne vrijednosti
        else{
            employee_id=0;
            shift1=1;
            shift2=tmp_shift2;    
        }
        $( "#datepicker" ).trigger("change");
        
    });

    //reagiranje na promjenu inputa datuma
    $( "#datepicker" ).on("change", function(){
        if($( "#datepicker" ).val()!=""){
            start=Number(hours_start)*60+Number(minutes_start); //treba iznova izračunati start jer ga kasnije možda promjenimo
            if(shift2===1){
                start_shift2=Number(hours_start_shift2)*60+Number(minutes_start_shift2); 
            }
            
            picked_date=$( "#datepicker" ).val();//pročitamo input
            $("#free-appointments").empty();//izbrišemo termine da bi ispisali nove
            //u slučaju da je trenutno vrijeme današnje
            if(picked_date==today){
                var t=(hh_now+1)*60;
                //ako je trenutno vrijeme usred radnog vremena, neka prvi termin bude od idućeg punog sata
                if(t>start && t<shift1_until){
                    start=t;
                    console.log(".today..1");
                }
                    
                if(shift2===1 && t>start_shift2 && t<shift2_until){
                    start_shift2=t;
                    start=end+1;;
                }
                //ako je prošlo radno vrijeme, onda datepicker postavljamo na sutradan
                else if((shift2 ===0 && t>=shift1_until) || (shift2===1 && t>=shift2_until)){
                    
                    var next_day=new Date();
                    next_day.setDate(next_day.getDate()+1);
                    var dd = String(next_day.getDate()).padStart(2, '0');
                    var mm = String(next_day.getMonth() + 1).padStart(2, '0'); 
                    var yyyy = next_day.getFullYear();
                    next_day = yyyy + '-' + mm + '-' + dd;
                    picked_date=next_day;
                    //postavljamo da se današnji dan ne može odabrati
                    $( "#datepicker" ).datepicker('destroy');
                    $( function() {
                        $( "#datepicker" ).datepicker({ minDate: next_day, maxDate: "4M" });
                    } );
                    $( "#datepicker" ).val(picked_date);
                }                    
            }
            
            //tu ide ajax
            calculate_free_appointments();

        }         
    });

//dohvaćamo termine rezervacija za određeni salon na određeni dan(šaljemo datum i id, dobivamo appointment_from, appointment_until i employee_id)
    function calculate_free_appointments(){
        var ima=0;
        picked_date=$( "#datepicker" ).val();
        
        $.ajax(
            {
                url: "index.php?rt=ajax/catchAppointmentsBySalonIdAndDate",
                
                data:
                {
                    
                    date: picked_date,
                    salon: salon_id    
                },
                type: "GET", 
                dataType: "json", 
                success: function(data){
                    console.log("dobio odgovor");
                    ima=0;//varijabla koja je 1 ako je ponuđeno ono vrijeme koje je spremljeno u clicked_time
                    //iteriramo po svim segmentima (od 15 min) od start do end
                    if(shift1===1){
                        for(var t=start; t<=end; t=t+15){
                            //provjeravamo narednih duration segmenata (da bi stao cijeli termin)
                            for(var j=0; j<duration*15; j+=15){
                                var br=0,booked=false;;//br je brojač koliko ima preklapajućih termina, booked je varijabla koja je true ako smo zaključili da termin nije slobodan
                                for(var i=0; i<data.length; ++i){
                                    //pretvaramo appointment_from i appointment_until u minute
                                    var [hours,minutes,seconds]=data[i]['appointment_from'].split(':');
                                    var ap_time_from=Number(hours)*60 + Number(minutes);
                                    var [hours,minutes,seconds]=data[i]['appointment_until'].split(':');
                                    var ap_time_until=Number(hours)*60 + Number(minutes);
                                    //ako je segment unutar [appointment_from,appointment_until> povečavamo brojač
                                    if(ap_time_from<=(t+j) && (t+j)<ap_time_until){
                                        ++br; 
                                        //ako je brojač jednak broju zaposlenika, onda taj termin nije moguć pa prekidamo petlju
                                        //ako je brojač jednak jedan te je id tog termina jednak id-u odabranog zaposlenika također prekidamo petlju
                                        //ako smo za jedan segment našli da ne paše, onda cijeli termin ne može stati pa prekidamo obje petlje i idemo na sljedeću iteraciju od t
                                        if(br===num_employees || ( br===1 && employee_id!==0 &&  data[i]['employee_id']===employee_id)){
                                            booked=true;
                                            break;
                                        }
                                    }
                                }
                                if(booked===true)   
                                    break;
                            }
                            //i preskačemo ovaj segment
                            if(booked===true)
                                continue;

                            //ako smo došli do ovdje, dodajemo gumb s pripadnim terminom
                            var btn=$("<button>");
                            btn.attr("class","time-btn");
                            //računamo sate i minute iz t koji je u minutama i upisujemo vrijeme u gumb
                            var now_h=Math.floor(t/60);
                            var now_m=Math.floor((t/60-now_h)*60);
                            if(now_m==0)
                                btn.html(now_h+"h");
                            else    
                                btn.html(now_h+":"+now_m+"h");
                            //ako je taj gumb upravo onaj koji smo prije klikli, označi ga i postavi ima na 1
                            if(btn.html()==clicked_time){
                                btn.css("background-color","#ffcccc");
                                $("#reserve").css("display","block");
                                ima=1;
                            }
                            if(reserve_clicked===0)
                                $("#free-appointments").append(btn);
                        }
                    }

                    //radimo isto za drugu smjenu
                    if(shift2===1){
                        for(var t=start_shift2; t<=end_shift2; t=t+15){
                            for(var j=0; j<duration*15; j+=15){
                                var br=0,booked=false;;
                                for(var i=0; i<data.length; ++i){
                                    var [hours,minutes,seconds]=data[i]['appointment_from'].split(':');
                                    var ap_time_from=Number(hours)*60 + Number(minutes);
                                    var [hours,minutes,seconds]=data[i]['appointment_until'].split(':');
                                    var ap_time_until=Number(hours)*60 + Number(minutes);
                                    
                                    if(ap_time_from<=(t+j) && (t+j)<ap_time_until){
                                        ++br; 
                                    
                                        if(br===num_employees_shift2 || ( br===1 && employee_id!==0 &&  data[i]['employee_id']===employee_id)){
                                            booked=true;
                                            break;
                                        }
                                    }
                                }
                                
                                if(booked===true)   
                                    break;
                            }
                            if(booked===true)
                                continue;

                            var btn=$("<button>");
                            btn.attr("class","time-btn");

                            var now_h=Math.floor(t/60);
                            var now_m=Math.floor((t/60-now_h)*60);
                            if(now_m==0)
                                btn.html(now_h+"h");
                            else    
                                btn.html(now_h+":"+now_m+"h");
                            if(btn.html()==clicked_time){
                                btn.css("background-color","#ffcccc");
                                $("#reserve").css("display","block");
                                ima=1;
                            }
                            if(reserve_clicked===0)
                                $("#free-appointments").append(btn);
                        }

                    }

                    //ako nismo dodali nijedan gumb, odnosno nema slobodnih termina, ispiši poruku
                    if(($("#free-appointments").children(".time-btn")).length===0)
                        $("#free-appointments").html("Nažalost, nema slobodnih termina"); 

                    //ako nema gumba s clicked_time sakrivamo gumb potvrdi rezervaciju
                    if(ima==0){
                        $("#reserve").css("display","none");
                    }
                    
                    if(reserve_clicked===1){
                        
                        if(ima===0){
                            $("#occupied").css("display","block");
                            $( "#datepicker" ).trigger("change");
                        }
                        else{
                            reserve();
                        } 
                        reserve_clicked=0; 
                    }

                },
                error: function(xhr,status,errorThrow)
                {
                    if( status !== null )
                        console.log( "Greška prilikom Ajax poziva: " + status +"  ,   " + errorThrow);
                    
    
                }
            }
        );
        
    }

    // klik na vrijeme-oboja se samo on drugom bojom, postavi se clicked_time i prikaže se gumb Potvrdi rezervaciju
    $("body").on("click",".time-btn", function(){
        $("#occupied").css("display","none");
        $(".time-btn").css("background-color","white");
        $(this).css("background-color","#ffcccc");
        clicked_time=$(this).html();
        $("#reserve").css("display","block");
    }) ;   

    //Gumb dodaj uslugu
    $("#add-service").on("click", function() {
        if($("#usluge-container2").length===0){
            //kloniramo istu onu tablicu kao na početnij stranici
            var cloned_table=$("#usluge-container").clone();
            cloned_table.attr("id","usluge-container2");
            $("#service-container").append(cloned_table);

            var btns=cloned_table.find(".book-btn");
            //reagiranje buttona rezerviraj
            btns.on("click", function() {
                reserve_on_click(Number($(this).attr("id")));
                btns.off("click");
                $("#service-container").empty();
            });
        }
        else{
            $("#service-container").empty();
        }
    });

    $("#reserve").on("click",function(){
        //$( "#datepicker" ).trigger("change");
        calculate_free_appointments();
        reserve_clicked=1;
    });
    //klik na gumb Potvrdi rezervaciju-formatiranje podataka i slanje preko ajaxa
    function reserve(){

            //postavljamo string koji ćemo spremiti u services
            var name=""
            for(var i=0;i<picked_services_name.length;++i){
                if(i==0)
                    name=picked_services_name[0];
                else
                    name = name+", "+ picked_services_name[i];
            }

            //postavljamo pravi format vremena za spremanje
            var k=clicked_time.length;
            var sliced=clicked_time.slice(0,k-1);
            var [h,m]=sliced.split(':'); 
            if(m==null)
                var until_in_minutes=Number(h)*60+15*duration; 
            else
                var until_in_minutes=Number(h)*60+Number(m)+15*duration;  
            if(h.length==1)
                h="0"+h;
            if(m==null)
                m="00";
            var time_str=h+":"+m+":00";
            var until_h=Math.floor(until_in_minutes/60);//računamo sate i minute
            var until_m=Math.floor((until_in_minutes/60-until_h)*60);
            until_h=String(until_h);
            until_m=String(until_m);
            if(until_h.length==1)
                until_h="0"+until_h;
            if(until_m==null || until_m=="0")
                until_m="00";
            var time_str_until=until_h+":"+until_m+":00";   

            //šaljemo podatke skripti koja će ih spremiti u bazu
            $.ajax(
                    {
                        url: "index.php?rt=ajax/saveAppointment",
                        data:
                        {
                            customer_id: customer_id,
                            hair_salon_id: salon_id,
                            employee_id: employee_id,
                            services: name,
                            date: picked_date,
                            appointment_from: time_str,
                            appointment_until: time_str_until,
                            duration: duration,
                            price: discount_price
                            
                        },
                        type: "GET", 
                        dataType: "json", 
                        success: function(data){
                            if(data=="uneseno"){
                                console.log("slanje uspjelo");
                                //zatvorimo modal za rezervaciju i otvorimo modal koji ispisuje poruku o uspješnoj rezervaciji
                                $("#close2").trigger("click");
                                $("#modal3").css("display", "block");
                            }
                        },
                        error: function(xhr,status,errorThrow){
                            if( status !== null )
                                console.log( "Greška prilikom Ajax poziva: " + status +"  ,   " + errorThrow);
                        }
                    }
                );
        
    }

    //klink na gumb za zatvaranje modala koji ispisuje potvrdu o uspješnoj rezervaciji-samo se sakrije modal
    $(".close3").on("click", function() {
        
        $("#modal3").css("display", "none");
        $("#modal4").css("display", "none");
    });
	
    //ako se dogodila prijava nakon klika na rezerviraj, otvori odgovarajući modal i izbriši iz storage
    if(sessionStorage.getItem("btn_id")!=null){
        $("#" + sessionStorage.getItem("btn_id")).trigger("click");
        sessionStorage.removeItem("btn_id")
    }

});