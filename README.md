
# Timski projekt iz kolegija RP2
* izradili: Lorena Novak, Nikola Sunara, Mateja Terzanović, Katarina Šupe
## PHP i JS web aplikacija korištenjem MVC arhitekturalnog obrasca

### Model: 
- `appService.class.php`
    - spajanje na bazu, dohvaćanje i promjena podataka
    
- `customer.class.php`
    - klasa koja predstavlja korisnika
    
- `salon.class.php`
    - klasa koja predstavlja salon
    
- `employee.class.php`
    - klasa koja predstavlja zaposlenika u salonu
    
- `service.class.php`
    - klasa koja predstavlja uslugu u salonu
    
- `discount.class.php` 
    - klasa koja predstavlja popust na uslugu u salonu

- `review.class.php` 
    - klasa koja predstavlja recenziju s komentarom i ocjenom za salon

- `notification.class.php` 
    - klasa koja predstavlja obavijest koja se prikazuje prijavljenim korisnicima ili salonima

- `appointment.class.php` 
    - klasa koja predstavlja rezervaciju termina u salonu od strane nekog korisnika



### View: 

- `_header.php`
    - prikazuje gornji izbornik, ispisuje gumbe ovisno o tome je li netko prijavljen i je li prijavljen korisnik ili salon 
    
- `_footer.php`
    - zatvara html
    
-  `_404_index.php`
    - error404
    
- `discounts_index.php`
    - ispis svih popusta
    
- `edit_gallery.php`
    - uređivanje galerije salona
    
- `homePage.php`
    - naslovna stranica

- `myprofile_index.php`
    - ispisuje profil korisnika ili salona, ovisno o tome koji je ulogiran
    
- `new_customer.php` 
    - forma za registraciju novog korisnika
    
- `new_salon.php`
    - forma za registraciju novog salona
    
- `notification_open.php`
    - ispis odabrane notifikacije
    
- `notifications.php`
    - ispis svih notifikacija

- `salon_info.php`
    - prikaz podataka o salonu (ime, opis, adresa, radno vrijeme, usluge, recenzije,...)
    
- `salons_index.php`
    - popis svih salona

- `successfull_preregistration.php`
    - ispis zahvale na registraciji nakon uspješne registracije

- `successfull_registration.php`
    - ispis poruke o uspješnoj potvrdi registracije 




### Controller:
- `loginController.php`
	- `loginPost()` - provjerava ispunjenu formu za login
	- `newCustomerPost()` - provjerava ispunjenu formu za registraciju korisnika
	- `newSalonPost()` - provjerava ispunjenu formu za registraciju salona
	- `logout()` - obrađuje logout

- `_404Controller.php`
    - `index()` - zove _404 view

- `homeController.php`
    - `index()` -preusmjerava na naslovnu

- `customerController.php`
    - `myprofile()` - dohvaca podatke o ulogiranom korisniku i preusmjerava na view `myprofile_index.php`
    - `updateMyProfile()` - poziva update na podacima prijavljenog korisnika i preusmjerava na view `myprofile_index.php`
        
- `salonsController.php`
    - `allSalons()` - dohvaca sve salone i zove view `salons_index.php`
    - `allDiscounts()` - dohvaca sve popuste i zove view `discounts_index.php`
    - `show ()` - dohvaca sve podatke o salonu i zove view `salon_info.php`
    - `myprofile()` - dohvaca sve podatke o ulogiranom salonu ili korisniku i zove view`myprofile_index.php`
    - `editGallery()` - dohvaca sve fotografije iz galerije te zove view `edit_gallery.php`
    - `updateMyProfile()` - updatea promjene na podacima prijavljenog korisnika ili salona
    - `addNewPicture()` - upload nove fotografije u galeriju 
        
- `notificationController.php`
    - `index()` - dohvaca sve notifikacije i zove view `notifications.php`
    - `show_notification()` - dohvaca notifikaciju po id-ju i zove view `notification_open.php`

- `ajaxController.php` (služi za odgovaranje na ajax pozive u .js fileovima)
    - `catchAppointmentsBySalonIdAndDate()`
    - `saveAppointment()`
    - `checkCustomerUsernameInBase()`
    - `removeAppointmentWithId()`
    - `insertInReviews()`
    - `updateRating()`
    - `changeFrontPage()`
    - `checkSalonUsernameInBase()`
    - `removeServiceInSalon()`
    - `removeEmployeeFromSalon()`
    - `updateServiceInSalon()`
    - `updateEmployee()`

- `index.php`
	- preusmjeravanje na ispravne stranice, početna stranica je home koja prikazuje `homePage.php` view
- `register.php`
	- registracija korisnika ili salona
    
#### app/database
- `db.class.php`
	- spajanje na bazu

- `create_tables.php`
	- kreiranje tablica u bazi

- `seed_tables.php`
	- punjenje tablica u bazi

#### js
- `discounts.js`
	- vrti trenutne popuste iz svih salona na aplikaciji

- `editGallery.js`
	- postavljanje nove naslovne fotografije

- `gallery.js`
	- galerija fotografija svakog salona

- `reservation.js`
	- rezervacija termina u salonu

- `reviews.js`
	- bojanje odabranih zvjezdica u recenzijama

- `validationCustomer.js`
	- promjene kod korisnika u Moj profil

- `validationSalon.js`
	- promjene kod salona u Moj salon

#### css
- `style2.css`
	- korišten css za izgled stranice

- `datepicker-style.css`
	- kalendar za rezervaciju termina

#### pictures
- fotografije dodane na stranicu

- `Logo.png`
	- logo naše web aplikacije

- Osim navedenog, u repozitoriju možete još vidjeti i priloženu prezentaciju u pdf formatu `BookALook.pdf`



    
