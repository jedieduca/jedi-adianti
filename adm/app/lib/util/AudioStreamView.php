<?php
class AudioStreamView extends TPage
{
    public function onStream($param)
    {
        try {
            if (empty($param['id'])) {
                throw new Exception('ID não informado.');
            }

            TTransaction::open('your_database'); // ajuste o nome do seu connection

            $audio = new AudioGerado((int) $param['id']);
            if (empty($audio->id)) {
                throw new Exception('Registro não encontrado.');
            }

            $path = $audio->file_path;

            // Segurança: bloqueia path traversal e exige arquivo existente
            if (!is_file($path)) {
                throw new Exception('Arquivo não encontrado no servidor.');
            }

            $mime = $audio->mime_type ?: OpenAITTSService::mimeFromFormat($audio->format);

            // Fecha transação antes de enviar o binário
            TTransaction::close();

            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($path));
            header('Content-Disposition: inline; filename="' . basename($path) . '"');
            header('Accept-Ranges: bytes');

            readfile($path);
            exit;

        } catch (Exception $e) {
            if (TTransaction::get()) {
                TTransaction::rollback();
            }
            // Como é streaming, devolver mensagem simples (ou logar)
            header('Content-Type: text/plain; charset=utf-8', true, 500);
            echo 'Erro: ' . $e->getMessage();
            exit;
        }
    }

    public function onStreamByPergunta($param)
    {
        //echo '<pre>'; print_r($param); echo '</pre>';
        try {
            // Segurança mínima: exigir login (ajuste se você usa outro padrão)
            if (!TSession::getValue('userid')) {
                header('Content-Type: text/plain; charset=utf-8', true, 401);
                echo 'Não autorizado.';
                exit;
            }
            //echo '<pre>'; print_r('58'); echo '</pre>';

            if (empty($param['id_pergunta'])) {
                throw new Exception('id_pergunta não informado.');
            }

            $idPergunta = (int) $param['id_pergunta'];
            if ($idPergunta <= 0) {
                throw new Exception('id_pergunta inválido.');
            }

            //echo '<pre>'; print_r('70'); echo '</pre>';
            $baseDir = 'app/storage/audios';
            $file = $baseDir . "/pergunta_{$idPergunta}.mp3";

            // Segurança: garante que está dentro da pasta prevista
            $baseDirReal = realpath($baseDir);
            $fileReal    = realpath($file);

            if (!$baseDirReal || !$fileReal || strpos($fileReal, $baseDirReal) !== 0) {
                throw new Exception('Caminho inválido.');
            }

            //echo '<pre>'; print_r('82'); echo '</pre>';
            if (!is_file($fileReal)) {
                header('Content-Type: text/plain; charset=utf-8', true, 404);
                echo 'Áudio não encontrado. Gere o MP3 primeiro.';
                exit;
            }

            header('Content-Type: audio/mpeg');
            header('Content-Length: ' . filesize($fileReal));
            header('Content-Disposition: inline; filename="' . basename($fileReal) . '"');
            header('Accept-Ranges: bytes');

            readfile($fileReal);
            exit;

        } catch (Exception $e) {
            header('Content-Type: text/plain; charset=utf-8', true, 500);
            echo 'Erro: ' . $e->getMessage();
            exit;
        }
    }

}
