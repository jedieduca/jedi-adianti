<?php
class ExportPergunta2Json extends TPage
{
    public function __construct()
    {
        parent::__construct();

        try
        {
            // abre transação
            TTransaction::open('jedieduca'); // troque pelo nome do seu banco

            $repo = new TRepository('Pergunta');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('publica', '=', 1));
            $criteria->add(new TFilter('analise_proposta', 'IS NOT', null));
            $criteria->add(new TFilter('fala_proposta', 'IS NOT', null));

            $perguntas = $repo->load($criteria);

            $out = [];

            if ($perguntas)
            {
                foreach ($perguntas as $p)
                {
                    $out[] = [
                        'id'              => (int) $p->id,
                        'idtema'          => $p->idtema !== null ? (int) $p->idtema : null,
                        'pergunta'        => $p->pergunta,
                        'respcerta'       => $p->respcerta,
                        'resp2'           => $p->resp2,
                        'resp3'           => $p->resp3,
                        'resp4'           => $p->resp4,
                        'caminhoimagem'   => $p->caminhoimagem,
                        'caract_proposta' => $p->caract_proposta,
                        'analise_proposta'=> $p->analise_proposta,
                        'analise_gpt'     => $p->analise_gpt,
                        'analise_gemini'  => $p->analise_gemini,
                        'origem_analise'  => $p->origem_analise !== null ? (int) $p->origem_analise : null,
                        'fala_gpt'        => $p->fala_gpt,
                        'fala_gemini'     => $p->fala_gemini,
                        'origem_fala'     => $p->origem_fala !== null ? (int) $p->origem_fala : null,
                        'fala_proposta'   => $p->fala_proposta,
                        'publica'         => (int) $p->publica,
                    ];
                }
            }

            // gera JSON bonito
            $json = json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            // caminho de saída
            $filename = 'app/output/pergunta.json';

            file_put_contents($filename, $json);

            TTransaction::close();

            // abre o arquivo no navegador
            //TPage::openFile($filename);

            new TMessage('info', 'Arquivo JSON gerado em: ' . $filename);
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
}
