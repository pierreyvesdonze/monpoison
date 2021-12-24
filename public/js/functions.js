

//Educations
var $addEducationButton = $('<button type="button" class="add-education-link"><i class="fas fa-plus-circle fa-2x"></button>');
var $newEducationLinkLi = $('<li></li>').append($addEducationButton);

//Skills
var $addSkillButton = $('<button type="button" class="add-skill-link"><i class="fas fa-plus-circle fa-2x"></button>');
var $newSkillLinkLi = $('<li></li>').append($addSkillButton);

//XP
var $addXpButton = $('<button type="button" class="add-experience-link"><i class="fas fa-plus-circle fa-2x"></button>');
var $newXpLinkLi = $('<li></li>').append($addXpButton);

//INTERESTS
var $addInterestButton = $('<button type="button" class="add-interest-link"><i class="fas fa-plus-circle fa-2x"></button>');
var $newInterestLinkLi = $('<li></li>').append($addInterestButton);


jQuery(document).ready(function () {

    //Educations
    $educationCollectionHolder = $('ul.educations');
    $educationCollectionHolder.find('.li-education').each(function () {
        addEducationFormDeleteLink($(this));
    });
    $educationCollectionHolder.append($newEducationLinkLi);
    $educationCollectionHolder.data('index', $educationCollectionHolder.find(':input').length);
    $addEducationButton.on('click', function (e) {
        addEducationForm($educationCollectionHolder, $newEducationLinkLi);
    });

    //Skills
    $skillCollectionHolder = $('ul.skills');
    $skillCollectionHolder.find('.li-skill').each(function () {
        addSkillFormDeleteLink($(this));
    });
    $skillCollectionHolder.append($newSkillLinkLi);
    $skillCollectionHolder.data('index', $skillCollectionHolder.find(':input').length);
    $addSkillButton.on('click', function (e) {
        addSkillForm($skillCollectionHolder, $newSkillLinkLi);
    });

    //XP
    $xpCollectionHolder = $('ul.experiences');
    $xpCollectionHolder.find('.li-experience').each(function () {
        addXpFormDeleteLink($(this));
    });
    $xpCollectionHolder.append($newXpLinkLi);
    $xpCollectionHolder.data('index', $xpCollectionHolder.find(':input').length);
    $addXpButton.on('click', function (e) {
        addXpForm($xpCollectionHolder, $newXpLinkLi);
    });

    //INTERESTS
    $interestCollectionHolder = $('ul.interests');
    $interestCollectionHolder.find('.li-interest').each(function () {
        addInterestFormDeleteLink($(this));
    });
    $interestCollectionHolder.append($newInterestLinkLi);
    $interestCollectionHolder.data('index', $interestCollectionHolder.find(':input').length);
    $addInterestButton.on('click', function (e) {
        addInterestForm($interestCollectionHolder, $newInterestLinkLi);
    });

});

// Educations
function addEducationForm($educationCollectionHolder, $newEducationLinkLi) {

    var prototype = $educationCollectionHolder.data('prototype');
    var index = $educationCollectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $educationCollectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="li-education"></li>').append(newForm);
    $newEducationLinkLi.before($newFormLi);

    // add a delete link to the new form
    addEducationFormDeleteLink($newFormLi);
}

function addEducationFormDeleteLink($educationFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-education-link"><i class="fas fa-minus-circle fa-2x"></button>');
    $educationFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $educationFormLi.remove();
    });
}


// Skills
function addSkillForm($skillCollectionHolder, $newSkillLinkLi) {

    var prototype = $skillCollectionHolder.data('prototype');
    var index = $skillCollectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $skillCollectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="li-skill"></li>').append(newForm);
    $newSkillLinkLi.before($newFormLi);

    // add a delete link to the new form
    addSkillFormDeleteLink($newFormLi);
}

function addSkillFormDeleteLink($skillFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-skill-link"><i class="fas fa-minus-circle fa-2x"></button>');
    $skillFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $skillFormLi.remove();
    });
}

// XP
function addXpForm($xpCollectionHolder, $newXpLinkLi) {

    var prototype = $xpCollectionHolder.data('prototype');
    var index = $xpCollectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $xpCollectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="li-experience"></li>').append(newForm);
    $newXpLinkLi.before($newFormLi);

    // add a delete link to the new form
    addXpFormDeleteLink($newFormLi);
}

function addXpFormDeleteLink($xpFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-experience-link"><i class="fas fa-minus-circle fa-2x"></button>');
    $xpFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $xpFormLi.remove();
    });
}

//INTERESTS
function addInterestForm($interestCollectionHolder, $newInterestLinkLi) {

    var prototype = $interestCollectionHolder.data('prototype');
    var index = $interestCollectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $interestCollectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="li-interest"></li>').append(newForm);
    $newInterestLinkLi.before($newFormLi);

    // add a delete link to the new form
    addInterestFormDeleteLink($newFormLi);
}

function addInterestFormDeleteLink($interestFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-interest-link"><i class="fas fa-minus-circle fa-2x"></button>');
    $interestFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $interestFormLi.remove();
    });
}
