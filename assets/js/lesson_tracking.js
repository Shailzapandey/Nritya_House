// File: assets/js/lesson_tracking.js

// 1. YouTube IFrame API Initialization
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubeIframeAPIReady() {
    // Check if the server passed the configuration object
    if (typeof window.LessonConfig === 'undefined' || !window.LessonConfig.videoId) {
        console.warn("Video configuration missing.");
        return;
    }

    player = new YT.Player('youtube-api-player', {
        videoId: window.LessonConfig.videoId,
        playerVars: { 'playsinline': 1, 'rel': 0, 'modestbranding': 1 },
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

// 2. State Listener
function onPlayerStateChange(event) {
    // 0 represents YT.PlayerState.ENDED
    if (event.data === 0) {
        autoCompleteLesson();
    }
}

// 3. The Background AJAX Sync
function autoCompleteLesson() {
    const statusDiv = document.getElementById('completion-status');

    // Abort if already completed or UI doesn't exist
    if (!statusDiv || statusDiv.innerText.includes('Completed')) return;
    if (typeof window.LessonConfig === 'undefined') return;

    // Read variables passed from PHP
    const classId = window.LessonConfig.classId;
    const lessonId = window.LessonConfig.lessonId;
    const baseUrl = window.LessonConfig.baseUrl;

    // Extract CSRF token securely from the DOM
    const csrfInput = document.querySelector('input[name="csrf_token"]');
    if (!csrfInput || !classId || !lessonId) return;

    const formData = new FormData();
    formData.append('class_id', classId);
    formData.append('lesson_id', lessonId);
    formData.append('csrf_token', csrfInput.value);
    formData.append('is_ajax', '1');

    statusDiv.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Saving...';

    fetch(baseUrl + '/lesson/complete', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the main badge
                statusDiv.style.background = '#10b981';
                statusDiv.innerHTML = '<i class="fa-solid fa-check"></i> Completed';

                // Dynamically add the checkmark to the syllabus sidebar
                const activeSyllabusItem = document.querySelector('.active-syllabus-item');
                if (activeSyllabusItem && !activeSyllabusItem.querySelector('.fa-circle-check')) {
                    activeSyllabusItem.innerHTML += '<i class="fa-solid fa-circle-check" style="color: #10b981; margin-left: 10px;"></i>';
                }
            }
        })
        .catch(error => {
            console.error('Network error during sync:', error);
            statusDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Error saving';
        });
}

// 4. Pro Tools (Mirroring & Speed)
let isMirrored = false;
function toggleMirror() {
    const wrapper = document.getElementById('video-transform-wrapper');
    const btn = document.getElementById('btn-mirror');
    if (!wrapper || !btn) return;

    isMirrored = !isMirrored;
    wrapper.style.transform = isMirrored ? 'scaleX(-1)' : 'scaleX(1)';
    btn.style.background = isMirrored ? 'var(--primary)' : 'transparent';
    btn.style.color = isMirrored ? 'white' : 'var(--primary)';
}

function setSpeed(rate) {
    if (player && typeof player.setPlaybackRate === 'function') {
        player.setPlaybackRate(rate);
    }
}