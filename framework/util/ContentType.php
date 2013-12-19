<?php
/**
 * Description of ContentType
 *
 * @author ypra
 */
class ContentType
{

    /* UTF-8 charset encode */
    const CHARSET_UTF8 = 'UTF-8';

    /* ISO-8859-1 charset encode */
    const CHARSET_ISO88591 = 'ISO-8859-1';

    /* Alternative files and attachments */
    const TYPE_ALTERNATIVE = 'multipart/alternative';

    /* Type for .au and .snd files */
    const TYPE_AUDIO = 'audio/basic';

    /* Strict type for binary date, include to downloads */
    const TYPE_BINARY = 'application/octet-stream';

    /* Microsoft Excel files */
    const TYPE_EXCEL = 'application/x-msexcel';

    /* Javascript contents */
    const TYPE_JAVASCRIPT = 'application/x-javascript';

    /* JSON contents */
    const TYPE_JSON = 'application/json';

    /* HiperText Markup Language contents */
    const TYPE_HTML = 'text/html';

    /* Mixed files and attachments */
    const TYPE_MIXED = 'multipart/mixed';

    /* PDF documents */
    const TYPE_PDF = 'application/pdf';

    /* Rich Text files */
    const TYPE_RICHTEXT = 'text/richtext';

    /* Rich Text Format contents */
    const TYPE_RTF = 'application/rtf';

    /* Shockwave Flash contents */
    const TYPE_SWF = 'application/x-shockwave-flash';

    /* Normal and plain texts */
    const TYPE_TEXT = 'text/plain';

    /* Wireless Application Protocol for movil devices apps */
    const TYPE_WAP = 'text/vnd.wap.wml';

    /* Microsoft Word files */
    const TYPE_WORD = 'application/x-msword';

    /* Extensible MarkUp Language contents */
    const TYPE_XML = 'application/xml';

    /* ZIP files */
    const TYPE_ZIP = 'application/zip';

}
?>