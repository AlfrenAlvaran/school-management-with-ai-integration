<div class="container mt-5">
    <h2 class="mb-4">Student Information</h2>
    <form action="/students/create-student" method="post" id="studentForm">
        <!-- STUDENT INFO -->
        <div class="row mb-3">
            <div class="col">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="col">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename">
            </div>
            <div class="col">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact">
        </div>

        <div class="mb-3">
            <label>Region</label>
            <select id="region" class="form-control" name="region" required></select>
        </div>
        <div class="mb-3">
            <label>Province</label>
            <select id="province" class="form-control" name="province" disabled required></select>
        </div>
        <div class="mb-3">
            <label>City / Municipality</label>
            <select id="city" class="form-control" name="city" disabled required></select>
        </div>
        <div class="mb-3">
            <label>Barangay</label>
            <select id="barangay" class="form-control" name="barangay" disabled required></select>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">House no. | Street</label>
            <textarea class="form-control" id="address" name="address" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label for="birthdate" class="form-label">Birthdate</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate">
        </div>

        <div class="mb-3">
            <label class="form-label">Sex</label>
            <select class="form-select" id="sex" name="sex">
                <option disabled selected>Choose...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <!-- PARENT INFO -->
        <h4 class="mt-4">Parent Information</h4>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="same_as_student" name="same_as_student">
            <label class="form-check-label" for="same_as_student">
                Same as Student Address
            </label>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="parent_firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="parent_firstname" name="parent_firstname">
            </div>
            <div class="col">
                <label for="parent_middlename" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="parent_middlename" name="parent_middlename">
            </div>
            <div class="col">
                <label for="parent_lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="parent_lastname" name="parent_lastname">
            </div>
        </div>

        <div class="mb-3">
            <label for="parent_contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="parent_contact" name="parent_contact">
        </div>
        <div class="mb-3">
            <label for="parent_occupation" class="form-label">Occupation</label>
            <input type="text" class="form-control" id="parent_occupation" name="parent_occupation">
        </div>

        <div class="mb-3">
            <label>Region</label>
            <select id="parent_region" class="form-control" name="parent_region"></select>
        </div>
        <div class="mb-3">
            <label>Province</label>
            <select id="parent_province" class="form-control" name="parent_province" disabled></select>
        </div>
        <div class="mb-3">
            <label>City / Municipality</label>
            <select id="parent_city" class="form-control" name="parent_city" disabled></select>
        </div>
        <div class="mb-3">
            <label>Barangay</label>
            <select id="parent_barangay" class="form-control" name="parent_barangay" disabled></select>
        </div>

        <div class="mb-3">
            <label for="parent_house_no" class="form-label">House no. | Street</label>
            <textarea class="form-control" id="parent_house_no" name="parent_house_no" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
const api = "https://psgc.gitlab.io/api/";

// --- Load regions ---
async function loadRegions(selectId){
    const res = await fetch(api + "regions/").then(r => r.json());
    const select = document.getElementById(selectId);
    select.innerHTML = "<option value=''>Select Region</option>";
    res.forEach(r => select.innerHTML += `<option value="${r.code}">${r.name}</option>`);
}

// --- Load provinces ---
async function loadProvinces(regionCode, selectProvinceId, selectCityId, selectBarangayId){
    const province = document.getElementById(selectProvinceId);
    const city = document.getElementById(selectCityId);
    const barangay = document.getElementById(selectBarangayId);

    city.disabled = true;
    city.innerHTML = "<option value=''>Select City</option>";
    barangay.disabled = true;
    barangay.innerHTML = "<option value=''>Select Barangay</option>";

    if(!regionCode) return;

    const provinces = await fetch(api + "regions/" + regionCode + "/provinces/").then(r => r.json());
    if(provinces.length > 0){
        province.disabled = false;
        province.innerHTML = "<option value=''>Select Province</option>";
        provinces.forEach(p => province.innerHTML += `<option value="${p.code}">${p.name}</option>`);
    } else {
        province.disabled = true;
        province.innerHTML = "<option value=''>No Province</option>";
        const cities = await fetch(api + "regions/" + regionCode + "/cities-municipalities/").then(r => r.json());
        city.disabled = false;
        city.innerHTML = "<option value=''>Select City</option>";
        cities.forEach(c => city.innerHTML += `<option value="${c.code}">${c.name}</option>`);
    }
}

// --- Load cities ---
async function loadCities(provinceCode, selectCityId, selectBarangayId){
    const city = document.getElementById(selectCityId);
    const barangay = document.getElementById(selectBarangayId);
    barangay.disabled = true;
    barangay.innerHTML = "<option value=''>Select Barangay</option>";

    if(!provinceCode) return;
    const cities = await fetch(api + "provinces/" + provinceCode + "/cities-municipalities/").then(r => r.json());
    city.disabled = false;
    city.innerHTML = "<option value=''>Select City</option>";
    cities.forEach(c => city.innerHTML += `<option value="${c.code}">${c.name}</option>`);
}

// --- Load barangays ---
async function loadBarangays(cityCode, selectBarangayId){
    const barangay = document.getElementById(selectBarangayId);
    if(!cityCode) return;
    const res = await fetch(api + "cities-municipalities/" + cityCode + "/barangays/").then(r => r.json());
    barangay.disabled = false;
    barangay.innerHTML = "<option value=''>Select Barangay</option>";
    res.forEach(b => barangay.innerHTML += `<option value="${b.code}">${b.name}</option>`);
}

// --- Event listeners ---
document.getElementById("region").addEventListener("change", e => loadProvinces(e.target.value,"province","city","barangay"));
document.getElementById("province").addEventListener("change", e => loadCities(e.target.value,"city","barangay"));
document.getElementById("city").addEventListener("change", e => loadBarangays(e.target.value,"barangay"));

document.getElementById("parent_region").addEventListener("change", e => loadProvinces(e.target.value,"parent_province","parent_city","parent_barangay"));
document.getElementById("parent_province").addEventListener("change", e => loadCities(e.target.value,"parent_city","parent_barangay"));
document.getElementById("parent_city").addEventListener("change", e => loadBarangays(e.target.value,"parent_barangay"));

// --- Initial load ---
loadRegions("region");
loadRegions("parent_region");

// --- Copy student address if checkbox checked ---
document.getElementById("same_as_student").addEventListener("change", function(){
    const checked = this.checked;
    const studentFields = ['region','province','city','barangay','address'];
    const parentFields  = ['parent_region','parent_province','parent_city','parent_barangay','parent_house_no'];

    parentFields.forEach((pField,i)=>{
        const sField = studentFields[i];
        document.getElementById(pField).value = checked ? document.getElementById(sField).value : '';
        document.getElementById(pField).disabled = checked;
    });
});
</script>
