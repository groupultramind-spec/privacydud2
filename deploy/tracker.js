(function() {
    // Generate UUID
    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // Get or create session ID
    let sessionId = sessionStorage.getItem('tracker_session_id');
    if (!sessionId) {
        sessionId = uuidv4();
        sessionStorage.setItem('tracker_session_id', sessionId);
        
        // Only ping 'enter' if it's a new session
        fetch('tracker.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'enter', session_id: sessionId })
        }).catch(e => console.error(e));
    }

    // Track when user leaves
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            const data = JSON.stringify({ action: 'leave', session_id: sessionId });
            navigator.sendBeacon('tracker.php', data);
        }
    });
})();
