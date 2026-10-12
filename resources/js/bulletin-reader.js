import { getDocument, GlobalWorkerOptions } from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.mjs?url';
import { PageFlip } from 'page-flip';

GlobalWorkerOptions.workerSrc = workerUrl;

async function mountBulletinReader(root) {
    const bookElement = root.querySelector('[data-book-pages]');
    const statusElement = root.querySelector('[data-reader-status]');
    const pageLabel = root.querySelector('[data-page-label]');
    const previousButton = root.querySelector('[data-page-previous]');
    const nextButton = root.querySelector('[data-page-next]');
    const isFrench = root.dataset.locale === 'fr';

    const setStatus = (message, isError = false) => {
        statusElement.textContent = message;
        statusElement.setAttribute('role', isError ? 'alert' : 'status');
        statusElement.hidden = false;
    };

    try {
        const pdf = await getDocument({ url: root.dataset.pdfUrl }).promise;
        const pageElements = [];
        const baseWidth = 430;
        const baseHeight = 608;

        for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            const pdfPage = await pdf.getPage(pageNumber);
            const baseViewport = pdfPage.getViewport({ scale: 1 });
            const scale = Math.min(baseWidth / baseViewport.width, baseHeight / baseViewport.height);
            const viewport = pdfPage.getViewport({ scale: scale * Math.min(window.devicePixelRatio || 1, 1.5) });
            const pageElement = document.createElement('div');
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d', { alpha: false });

            pageElement.className = 'bulletin-book-page';
            canvas.width = Math.ceil(viewport.width);
            canvas.height = Math.ceil(viewport.height);
            canvas.style.width = `${baseWidth}px`;
            canvas.style.height = `${baseHeight}px`;
            await pdfPage.render({ canvas, canvasContext: context, viewport }).promise;
            pageElement.append(canvas);
            pageElements.push(pageElement);
        }

        bookElement.replaceChildren(...pageElements);
        const pageFlip = new PageFlip(bookElement, {
            width: baseWidth,
            height: baseHeight,
            size: 'stretch',
            minWidth: 280,
            maxWidth: 860,
            minHeight: 396,
            maxHeight: 1216,
            showCover: true,
            usePortrait: true,
            autoSize: true,
            maxShadowOpacity: 0.28,
            flippingTime: 650,
            mobileScrollSupport: false,
        });

        const updateControls = (pageIndex = pageFlip.getCurrentPageIndex()) => {
            const firstPage = pageIndex + 1;
            const lastPage = Math.min(firstPage + (pageFlip.getOrientation() === 'landscape' ? 1 : 0), pdf.numPages);
            pageLabel.textContent = isFrench
                ? `Pages ${firstPage}–${lastPage} sur ${pdf.numPages}`
                : `Pages ${firstPage}–${lastPage} of ${pdf.numPages}`;
            previousButton.disabled = pageIndex === 0;
            nextButton.disabled = pageIndex >= pdf.numPages - 1;
        };

        pageFlip.on('flip', event => updateControls(event.data));
        pageFlip.on('changeOrientation', () => updateControls());
        pageFlip.loadFromHTML(pageElements);
        updateControls();
        statusElement.hidden = true;

        previousButton.addEventListener('click', () => pageFlip.flipPrev());
        nextButton.addEventListener('click', () => pageFlip.flipNext());
        root.addEventListener('keydown', event => {
            if (event.key === 'ArrowLeft') pageFlip.flipPrev();
            if (event.key === 'ArrowRight') pageFlip.flipNext();
        });
    } catch (error) {
        setStatus(isFrench
            ? 'Impossible d’afficher ce bulletin. Téléchargez le PDF pour le consulter.'
            : 'This bulletin could not be displayed. Download the PDF to read it.', true);
        console.error('Unable to load parish bulletin PDF:', error);
    }
}

document.querySelectorAll('[data-bulletin-reader]').forEach(mountBulletinReader);