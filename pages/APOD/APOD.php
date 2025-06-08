<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Astronomy Picture of the Day - Cosmic Horizons</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-space-blue: #0B1746;
            --nebula-purple: #4A2C5D;
            --hologram-cyan: #00FFD4;
            --starlight-white: #E6E6FA;
            --glow-pink: #FF00FF;
            --cosmic-black: #000000;
            --danger-red: #FF4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            min-height: 100vh;
            font-family: 'Share Tech Mono', monospace;
            background: linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            color: var(--starlight-white);
            line-height: 1.6;
            position: relative;
            overflow-x: hidden;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 0, 0, 0.15),
                rgba(0, 0, 0, 0.15) 1px,
                transparent 1px,
                transparent 2px
            );
            pointer-events: none;
            z-index: 1000;
        }

        .cosmic-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 30% 50%, rgba(0,255,212,0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(255,0,255,0.1) 0%, transparent 50%),
                linear-gradient(135deg, var(--deep-space-blue), var(--nebula-purple));
            background-attachment: fixed;
            opacity: 0.8;
            z-index: -1;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 100;
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 
                0 0 10px rgba(0,255,212,0.7),
                0 0 20px rgba(0,255,212,0.5),
                0 0 30px rgba(0,255,212,0.3);
            animation: pulse 4s infinite alternate;
        }

        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.02); }
        }

        .button-group {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        button {
            padding: 1rem 2rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 8px;
            border: 2px solid var(--hologram-cyan);
            background: transparent;
            color: var(--hologram-cyan);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg, 
                transparent, 
                rgba(0,255,212,0.3), 
                transparent
            );
            transition: all 0.4s ease;
            z-index: -1;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 10px 20px rgba(0,255,212,0.3),
                0 0 30px rgba(0,255,212,0.5);
            background: rgba(0,255,212,0.1);
        }

        button:active {
            transform: translateY(-1px);
        }

        .apod-container {
            background: rgba(11, 23, 70, 0.6);
            border: 1px solid var(--hologram-cyan);
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
            backdrop-filter: blur(15px);
            box-shadow: 
                0 0 30px rgba(0, 255, 212, 0.2),
                inset 0 0 20px rgba(0, 255, 212, 0.05);
            position: relative;
            overflow: hidden;
        }

        .apod-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg, 
                transparent, 
                rgba(0,255,212,0.05), 
                transparent
            );
            transform: rotate(-45deg);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .apod-container:hover::before {
            opacity: 1;
        }

        .apod-container h2, .apod-container h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--hologram-cyan);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(0, 255, 212, 0.5);
        }

        .apod-container h2 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .apod-container p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        .apod-container strong {
            color: var(--hologram-cyan);
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .apod-container img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 
                0 10px 30px rgba(0,0,0,0.5),
                0 0 20px rgba(0,255,212,0.2);
            transition: transform 0.3s ease;
        }

        .apod-container img:hover {
            transform: scale(1.02);
        }

        .apod-container a {
            color: var(--hologram-cyan);
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            z-index: 5;
            display: inline-block;
            padding: 5px 10px;
            margin: 10px 0;
            background: rgba(11, 23, 70, 0.8);
            border-radius: 4px;
        }

        .apod-container a:hover {
            border-bottom-color: var(--hologram-cyan);
            text-shadow: 0 0 5px rgba(0, 255, 212, 0.5);
            background: rgba(11, 23, 70, 1);
            transform: translateY(-2px);
        }

        .loading {
            text-align: center;
            color: var(--hologram-cyan);
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: loadingPulse 2s infinite;
        }

        @keyframes loadingPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .error {
            color: var(--danger-red);
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid var(--danger-red);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: errorShake 0.5s ease-in-out;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
                margin-bottom: 2rem;
                letter-spacing: 2px;
            }

            .button-group {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }

            button {
                width: 100%;
                max-width: 300px;
                font-size: 1rem;
                padding: 0.8rem 1.5rem;
            }

            .apod-container {
                padding: 1.5rem;
                margin: 1.5rem 0;
            }

            .apod-container h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0.5rem;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                letter-spacing: 1px;
            }

            button {
                font-size: 0.9rem;
                padding: 0.7rem 1.2rem;
            }

            .apod-container {
                padding: 1rem;
                margin: 1rem 0;
            }

            .apod-container h2 {
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }

            .apod-container p {
                font-size: 0.9rem;
                line-height: 1.6;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            button {
                min-height: 44px;
                padding: 1rem 2rem;
            }

            button:hover {
                transform: none;
                box-shadow: none;
            }
        }
    </style>
  </head>
  <body>
    <div class="overlay"></div>
    <div class="cosmic-background"></div>
    
    <div class="container">
        <h1>🌌 Astronomy Picture of the Day</h1>
        
        <div class="button-group">
            <button onclick="getAPOD()">Get Today's APOD</button>
            <button onclick="getRandomAPOD()">Get Random APOD</button>
        </div>

        <div id="content"></div>
    </div>

    <script>
        const NASA_API_KEY = 'LJlgV7fzKLjYf3VOcGLs3hrAuB1ftiDdFK0FeuIJ';

        // Get today's Astronomy Picture of the Day
        async function getAPOD() {
            const contentDiv = document.getElementById('content');
            contentDiv.innerHTML = '<div class="loading">Loading today\'s APOD...</div>';

            try {
                const response = await fetch(`https://api.nasa.gov/planetary/apod?api_key=${NASA_API_KEY}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('APOD Data:', data); // Correct: console.log, not console.json
                
                displayAPOD(data);
                
            } catch (error) { // Correct: .catch() or try/catch, not .error()
                console.error('Error fetching APOD:', error);
                contentDiv.innerHTML = `<div class="error">Error: ${error.message}</div>`;
            }
        }

        // Get single random APOD
        async function getRandomAPOD() {
            const contentDiv = document.getElementById('content');
            contentDiv.innerHTML = '<div class="loading">Loading random APOD...</div>';

            try {
                const response = await fetch(`https://api.nasa.gov/planetary/apod?api_key=${NASA_API_KEY}&count=1`);
                const data = await response.json();
                
                console.log('Random APOD Data:', data);
                
                // Display single random image (data is an array with 1 item when using count parameter)
                if (data && data.length > 0) {
                    displayAPOD(data[0]);
                } else {
                    contentDiv.innerHTML = '<div class="error">No random APOD found.</div>';
                }
                
            } catch (error) {
                console.error('Error:', error);
                contentDiv.innerHTML = `<div class="error">Error: ${error.message}</div>`;
            }
        }



        // Display single APOD
        function displayAPOD(data) {
            const contentDiv = document.getElementById('content');
            
            let html = `<div class="apod-container">
                <h2>${data.title}</h2>
                <p><strong>Date:</strong> ${data.date}</p>`;
            
            if (data.media_type === 'image') {
                html += `<img src="${data.url}" alt="${data.title}">`;
                if (data.hdurl) {
                    html += `<p><a href="${data.hdurl}" target="_blank">View HD Version</a></p>`;
                }
            } else {
                html += `<p>Video content: <a href="${data.url}" target="_blank">View Video</a></p>`;
            }
            
            html += `<p>${data.explanation}</p>`;
            
            if (data.copyright) {
                html += `<p><strong>Copyright:</strong> ${data.copyright}</p>`;
            }
            
            html += '</div>';
            
            contentDiv.innerHTML = html;
        }

        // Alternative syntax using .then() chains (both work!)
        function getAPODWithThen() {
            fetch(`https://api.nasa.gov/planetary/apod?api_key=${NASA_API_KEY}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('APOD Data:', data);
                    displayAPOD(data);
                })
                .catch(error => { // Correct: .catch(), not .error()
                    console.error('Error:', error);
                    document.getElementById('content').innerHTML = `<div class="error">Error: ${error.message}</div>`;
                });
        }
    </script>
</body>
</html>