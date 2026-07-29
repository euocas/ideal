<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public static function gerar(
        string $view,
        array $dados = [],
        string $nomeArquivo = 'documento.pdf',
        string $orientacao = 'landscape'
    ): void {

        $relatorio = $dados;

        ob_start();

        require __DIR__ . '/../Views/' . $view . '.php';

        $html = ob_get_clean();

        $logPath = __DIR__ . '/../storage/dompdf_tmp/dompdf.log';

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('tempDir',__DIR__ . '/../storage/dompdf_tmp');

        $options->set('logOutputFile', $logPath);
        $options->set('debugPng', true);
        $options->set('debugKeepTemp', true);


        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', $orientacao);

        $dompdf->render();


        $dompdf->stream($nomeArquivo, [
            'Attachment' => false
        ]);
    }
}
