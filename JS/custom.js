// Display the current year in the footer
function getYear() {
    const currentYear = new Date().getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}
getYear();

// Initialize nice select dropdown
$(document).ready(function () {
    $('select').niceSelect();
});

// Initialize date picker for general inputs
$(function () {
    $("#inputDate").datepicker({
        autoclose: true,
        todayHighlight: true,
    }).datepicker('update', new Date());
});

// Initialize team carousel slider
$('.team_carousel').owlCarousel({
    loop: true,
    margin: 15,
    dots: true,
    autoplay: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    autoplayHoverPause: true,
    responsive: {
        0: { items: 1, margin: 0 },
        576: { items: 2 },
        992: { items: 3 }
    }
});

// Fetch unavailable dates for appointment date picker
$(document).ready(function () {
    const doctorId = 1; // Replace with dynamic doctor ID if necessary
    $.ajax({
        url: 'fetch_unavailable_dates.php',
        method: 'POST',
        data: { doctor_id: doctorId },
        success: function (response) {
            const unavailableDates = JSON.parse(response);
            $('#appointment-date').datepicker({
                format: 'yyyy-mm-dd',
                daysOfWeekDisabled: [0, 6], // Disable weekends
                beforeShowDay: function (date) {
                    const dateString = date.toISOString().split('T')[0];
                    return unavailableDates.includes(dateString)
                        ? { classes: 'disabled-date' }
                        : true;
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch unavailable dates:', error);
        }
    });
});

// Toggle between login and signup forms
const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signInForm = document.getElementById('signIn');
const signUpForm = document.getElementById('signup');

signUpButton.addEventListener('click', function () {
    signInForm.style.display = "none";
    signUpForm.style.display = "block";
});

signInButton.addEventListener('click', function () {
    signInForm.style.display = "block";
    signUpForm.style.display = "none";
});

// Show specialization field for doctors
document.getElementById('role').addEventListener('change', function () {
    const specializationGroup = document.getElementById('specialization-group');
    this.value === 'doctor'
        ? specializationGroup.classList.add('show')
        : specializationGroup.classList.remove('show');
});
