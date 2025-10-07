window.onload = () => {
    if (window.location.href == 'http://localhost:8000/'){
        let audioTags = document.getElementsByClassName('audio');
        for (let i = 0; i < audioTags.length; i++){
            audioTags[i].volume = 0.1;
        }
    }
};