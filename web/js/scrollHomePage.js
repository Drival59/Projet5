var homeElt = document.getElementById('homeScroll');
var newsElt = document.getElementById('newsScroll');
var forumElt = document.getElementById('forumScroll');
var contactElt = document.getElementById('contactScroll');
var homeSectionElt = document.getElementById('metalBanner');
var newsSectionElt = document.getElementById('lastsNewsCategory');
var forumSectionElt = document.getElementById('forumHome');
var contactSectionElt = document.getElementById('contact');

function scroll(section) {
  window.scrollTo({
    'behavior': 'smooth',
    'speed': 2,
    'left' : 0,
    'top': section.offsetTop
  });
}

homeElt.addEventListener('click', function (e) {
  e.preventDefault();
  scroll(homeSectionElt);
})

newsElt.addEventListener('click', function (e) {
  e.preventDefault();
  scroll(newsSectionElt);
})

forumElt.addEventListener('click', function (e) {
  e.preventDefault();
  scroll(forumSectionElt);
})

contactElt.addEventListener('click', function (e) {
  e.preventDefault();
  scroll(contactSectionElt);
})
