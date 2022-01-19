self.addEventListener('install', evt => {
    console.log('install evt', evt);

})

self.addEventListener('fetch', evt => {
    console.log('events captures : ');
    console.log('fetch evt sur url', evt.request.url);

}) 