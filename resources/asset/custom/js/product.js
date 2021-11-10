if ($('.i-youtube-url').length) {
    $('.i-youtube-url').each(function() {
        $(this).on('change', function(e) {
            const url = $(this).val();

            // loading
            $(this).closest('div').prepend(`<div class="loading">${ANH.loading.icon}</div>`);

            fetchThumbnail(url, $(this));
        });

        if ($(this).val()) {
            const url = $(this).val();
            fetchThumbnail(url, $(this));
        }
    });

    function fetchThumbnail(url, currentElement) {
        const el = $(currentElement).closest('div');
        const index = $(currentElement).data('index');

        console.log(index);

        $.get(ANH.url.web + '/remote', {act: 'youtube_thumbnail', quality: 'medium', url: url}, function(data, status, xhr) {

            const label = typeof index != 'undefined' ? 'Thumbnail '+ (index + 1) : '';

            if (data) {
                const html = `
                    <div>
                        <label>${label}</label>
                        <img src="${data}" class="img-responsive" />
                        <input type="hidden" name="youtube_url[${index}][thumbnail]" value="${data}" />
                        <br>
                    </div>
                `;

                if ($(el).find('div').not('.loading').length) {
                    $(el).find('div').not('.loading').replaceWith(html);
                }
                else {
                    $(el).prepend(html);
                }
            }
            else {
                $(el).find('div').not('.loading').remove();
            }
        }).done(function() {
            $('.loading').remove();
        });
    }
}