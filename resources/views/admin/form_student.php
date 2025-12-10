 <div class="container mt-5">
     <h2 class="mb-4">Student Information Form</h2>
     <form action="/students/create-student" method="post">
         <div class="row mb-3">
             <div class="col">
                 <label for="firstname" class="form-label">First Name</label>
                 <input type="text" class="form-control" id="firstname" placeholder="Enter first name" name="firstname">
             </div>

             <div class="col">
                 <label for="middlename" class="form-label">Middle Name</label>
                 <input type="text" class="form-control" id="middlename" placeholder="Enter middle name" name="middlename">
             </div>

             <div class="col">
                 <label for="lastname" class="form-label">Last Name</label>
                 <input type="text" class="form-control" id="lastname" placeholder="Enter last name" name="lastname">
             </div>

         </div>

         <div class="mb-3">
             <label for="email" class="form-label">Email</label>
             <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
         </div>

         <div class="mb-3">
             <label for="contact" class="form-label">Contact</label>
             <input type="text" class="form-control" id="contact" placeholder="Enter contact number" name="contact">
         </div>

         <div class="mb-3">
             <label>Region</label>
             <select id="region" class="form-control" name="region">
                 <option value="">Loading regions...</option>
             </select>
         </div>

         <div class="mb-3">
             <label>Province</label>
             <select id="province" class="form-control" disabled name="province">
                 <option value="">Select Region First</option>
             </select>
         </div>

         <div class="mb-3">
             <label>City / Municipality</label>
             <select id="city" class="form-control" disabled name="city">
                 <option value="">Select Province / Region First</option>
             </select>
         </div>

         <div class="mb-3">
             <label>Barangay</label>
             <select id="barangay" class="form-control" disabled name="barangay">
                 <option value="">Select City First</option>
             </select>
         </div>


         <div class="mb-3">
             <label for="address" class="form-label">House no. | Street name </label>
             <textarea class="form-control" id="address" rows="3" placeholder="Enter address" name="address"></textarea>
         </div>

         <div class="mb-3">
             <label for="birthdate" class="form-label">Birthdate</label>
             <input type="date" class="form-control" id="birthdate" name="birthdate">
         </div>

         <div class="mb-3">
             <label class="form-label">Sex</label>
             <select class="form-select" id="sex" name="sex">
                 <option selected disabled>Choose...</option>
                 <option value="Male">Male</option>
                 <option value="Female">Female</option>
             </select>
         </div>

         <button type="submit" class="btn btn-primary">Submit</button>
     </form>
 </div>


 <script>
     const api = "https://psgc.gitlab.io/api/";

     // Load Regions
     async function loadRegions() {
         const regions = await fetch(api + "regions/").then(r => r.json());
         const region = document.getElementById("region");
         region.innerHTML = "<option value=''>Select Region</option>";
         regions.forEach(r => {
             region.innerHTML += `<option value="${r.code}">${r.name}</option>`;
         });
     }

     // Load Provinces (or Cities directly if region has no provinces)
     async function loadProvinces(regionCode) {
         const province = document.getElementById("province");
         const city = document.getElementById("city");
         const barangay = document.getElementById("barangay");

         city.setAttribute("disabled", true);
         city.innerHTML = "<option value=''>Select Province / Region First</option>";
         barangay.setAttribute("disabled", true);
         barangay.innerHTML = "<option value=''>Select City First</option>";

         const provinces = await fetch(api + "regions/" + regionCode + "/provinces/").then(r => r.json());

         if (provinces.length > 0) {
             province.removeAttribute("disabled");
             province.style.display = "block";
             province.innerHTML = "<option value=''>Select Province</option>";
             provinces.forEach(p => {
                 province.innerHTML += `<option value="${p.code}">${p.name}</option>`;
             });
             city.setAttribute("disabled", true);
             city.innerHTML = "<option value=''>Select Province First</option>";
         } else {
             // Region has no provinces (NCR, CAR, BARMM)
             province.setAttribute("disabled", true);
             province.innerHTML = "<option value=''>No Province</option>";
             province.style.display = "none";

             const cities = await fetch(api + "regions/" + regionCode + "/cities-municipalities/").then(r => r.json());
             city.removeAttribute("disabled");
             city.innerHTML = "<option value=''>Select City / Municipality</option>";
             cities.forEach(c => {
                 city.innerHTML += `<option value="${c.code}">${c.name}</option>`;
             });
         }
     }

     // Load Cities
     async function loadCities(provinceCode) {
         const city = document.getElementById("city");
         const barangay = document.getElementById("barangay");

         city.removeAttribute("disabled");
         city.innerHTML = "<option value=''>Loading cities...</option>";

         barangay.setAttribute("disabled", true);
         barangay.innerHTML = "<option value=''>Select City First</option>";

         const cities = await fetch(api + "provinces/" + provinceCode + "/cities-municipalities/").then(r => r.json());
         city.innerHTML = "<option value=''>Select City / Municipality</option>";
         cities.forEach(c => {
             city.innerHTML += `<option value="${c.code}">${c.name}</option>`;
         });
     }

     // Load Barangays
     async function loadBarangays(cityCode) {
         const barangay = document.getElementById("barangay");
         barangay.removeAttribute("disabled");
         barangay.innerHTML = "<option value=''>Loading barangays...</option>";

         const barangays = await fetch(api + "cities-municipalities/" + cityCode + "/barangays/").then(r => r.json());
         barangay.innerHTML = "<option value=''>Select Barangay</option>";
         barangays.forEach(b => {
             barangay.innerHTML += `<option value="${b.code}">${b.name}</option>`;
         });
     }

     // Event Listeners
     document.getElementById("region").addEventListener("change", function() {
         loadProvinces(this.value);
     });

     document.getElementById("province").addEventListener("change", function() {
         loadCities(this.value);
     });

     document.getElementById("city").addEventListener("change", function() {
         loadBarangays(this.value);
     });

     // Initial Load
     loadRegions();


     document.querySelector('form').addEventListener('submit', function(e) {
         document.getElementById('region_name').value = document.getElementById('region').selectedOptions[0].text;
         document.getElementById('province_name').value = document.getElementById('province').selectedOptions[0].text;
         document.getElementById('city_name').value = document.getElementById('city').selectedOptions[0].text;
         document.getElementById('barangay_name').value = document.getElementById('barangay').selectedOptions[0].text;

         // Enable selects in case they are disabled
         document.getElementById('region').disabled = false;
         document.getElementById('province').disabled = false;
         document.getElementById('city').disabled = false;
         document.getElementById('barangay').disabled = false;
     });
 </script>