let playingBox = $('#playing');
let queueBox = $('#queue');

let volume = document.getElementById('volume');
let play = document.getElementById('play');
let pause = document.getElementById('pause');
let next = document.getElementById('next');
let back = document.getElementById('back');
let banner = document.getElementById('banner');
let time = document.getElementById('current-time');
let timeline = document.getElementById('timeline');
let end = document.getElementById('end-time');
let timelineUpdateInterval;

let songsList = [];
let radiosList = [];
let queueList = [];
let currentTrack;
let sounds = [];
const DEFAULTVOLUME = 0.65;


function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
}

// Function to initialize Howl instances
function howlSounds() {
    sounds = songsList.map(song => new Howl({
        src: [song.src],
        html5: true,
        onend: () => { nextTrack(); },
        volume: DEFAULTVOLUME,
        onplay: () => {
            pauseRadio();
            setupEqualizer();
            animateEqualizer();
        }
    }));

    radios = radiosList.map(radio => new Howl({
        src: [radio.src],
        html5: true,
        volume: DEFAULTVOLUME,
        onplay: () => {
            pauseTrack();
            setupEqualizer();
            animateEqualizer();
        }
    }))
}

// Function to update the queue UI
function updatePlaylist() {
    const queue = document.getElementById('queue');
    queue.innerHTML = ''; // Clear existing list
    songsList.forEach((track, index) => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        const album = document.createElement('p');
        const author = document.createElement('p');
        const hr = document.createElement('hr');

        a.textContent = track.title;
        a.onclick = () => {
            if (currentTrack !== index) {
                currentTrack = index;
                playTrack();
            }
        };

        li.appendChild(a);

        a.classList.add("flex");

        album.classList.add("album", "flex");
        album.textContent = track.album;
        album.setAttribute('title', `Album`);

        author.classList.add("author");
        author.textContent = track.author;
        author.setAttribute('title', `Author`);

        queue.appendChild(li);
        queue.appendChild(album);
        queue.appendChild(author);
        queue.appendChild(hr);
    });

    queueList = queue.getElementsByTagName("a");
}

function disableTimeline() {
    timeline.setAttribute("disabled");
}

function playRadio() {

}

function pauseRadio() {
    let radio = radiosList[currentRadio];

    radio.kill();
}

function updateTimeline(sound) {
    const currentTime = sound.seek(); // Get current playback time
    const duration = sound.duration(); // Get total duration of the song

    // Update current time display
    time.textContent = formatTime(currentTime);

    // Update the timeline slider
    const progress = (currentTime / duration) * 100;
    time.value = progress;
    timeline.value = progress;
}

function updateBanner(song) {
    if (song) {
        let url = songsList[currentTrack]["img"];
        banner.style = `background-image: url(${url});`;
    } else {
        let url = radiosList[currentRadio]["banner"];
        banner.style = `background-image: url(${url});`;
    }

}

// Function to play the current track
function playTrack() {
    // Stop all other sounds first
    sounds.forEach((sound, index) => {
        if (!currentTrack) {
            currentTrack = 0;
        }

        if (index !== currentTrack) {
            sound.stop();
            queueList[index].classList.remove("now-playing");
        } else {
            queueList[index].classList.add("now-playing");
            sound.on('load', () => {
                // Get the duration once the sound is loaded
                const duration = formatTime(sound.duration());
                end.innerText = duration;
            });
            sound.play();
            updateBanner(true);

            if (!timeline.value) {
                timeline.value = 0;
            }

            if (timelineUpdateInterval) {
                clearInterval(timelineUpdateInterval);
            }

            timelineUpdateInterval = setInterval(() => updateTimeline(sound), 1000);
        }
    });

    play.classList.add("hide");
    pause.classList.remove("hide");
}

function pauseTrack() {
    const sound = sounds[currentTrack];

    if (sound) {
        sound.pause();
        play.classList.remove("hide");
        pause.classList.add("hide");
    }
}

function nextTrack() {
    timeline.value = 0;

    currentTrack = (currentTrack + 1) % sounds.length; // Loop back to the first track
    playTrack();
}

function backTrack() {
    const sound = sounds[currentTrack];

    // Si la canción no ha sonado por 5 segundos, va a la anterior, o a la última si es la primera
    if (sound.seek() < 5) {
        sound.pause();
        timeline.value = 0;

        currentTrack = (currentTrack - 1 + sounds.length) % sounds.length;
    } else {
        // Si ha sonado por más de 5 segundos, la empieza de nuevo
        sound.seek(0);
    }

    playTrack();
}

function jumpTrack() {
    if (sounds[currentTrack]) {
        const sound = sounds[currentTrack];
        const duration = sound.duration(); // Get the total duration of the song

        // Calculate the new time based on the timeline value
        const newTime = (timeline.value / 100) * duration;

        // Seek to the new position
        sound.seek(newTime);
    }
}

function changeVolume() {
    if (sounds[currentTrack]) {
        const sound = sounds[currentTrack];
        sound.volume(volume.value / 100);
    }
}

// Load songs from JSON file
const loadSongs = async () => {
    let response = await fetch('../data/songs.json');
    songsList = await response.json();
    howlSounds(); // Initialize Howl instances after loading songs
    updatePlaylist(); // Update the queue UI after songs are loaded
}

// When the page loads, load the songs
loadSongs();

// Play and pause button actions
play.onclick = playTrack;
pause.onclick = pauseTrack;
next.onclick = nextTrack;
back.onclick = backTrack;
timeline.oninput = jumpTrack;
volume.oninput = changeVolume;
