<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube Downloader</title>
    <script>
        window.addEventListener('load', () => {

            const video = document.getElementById('video')

            document.getElementById('ytd_form').addEventListener('submit', (e) => {
                e.preventDefault()
                const url = e.target.url.value
                const xhr = new XMLHttpRequest()
                xhr.responseType = 'json'
                xhr.open('GET', 'video.php?url=' + url, true)
                xhr.addEventListener('load', (e) => {

                    console.log(e.target.response)

                    const formats = e.target.response.formats
                    const error = e.target.response.error

                    if (error) {
                        alert('Error: ' + error)
                        return
                    }

                    const fmt = formats[0]

                    if (!fmt) {
                        alert('Error: Video not found.')
                    }

                    video.src = fmt.url
                    video.width = fmt.width
                    video.height = fmt.height
                    video.play()

                })
                xhr.send()
            })
        })
    </script>
</head>
<body>
    <form method="get" id="ytd_form">
        <input type="text" value="https://www.youtube.com/watch?v=aqz-KE-bpKQ" name="url" size="80" />
        <button type="submit">Go</button>
    </form>

    <video width="800" height="600" controls id="video">
        <source src="" type="video/mp4"/>
        <em>Sorry, your browser doesn't support HTML5 video.</em>
    </video>

</body>
</html>