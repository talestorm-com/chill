<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of MediaContentController
 *
 * @author eve
 */
class MediaContentController extends AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    protected function actionBanner() {
        $this->render_view("admin", "banner");
    }

    protected function API_get(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $content_type = \DataMap\InputDataMap::F()->get_filtered("content_type", ["Strip", "Trim", 'NEString', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid_request");
        if (!$content_type) {
            $content_type = $this->read_content_type($id);
        }
        $content_type ? 0 : \Errors\common_error::R("no content type");
        $reader_method = "API_get_{$content_type}";
        method_exists($this, $reader_method) ? 0 : \Errors\common_error::RF("no reader method for content type '%s' in %s", $content_type, __METHOD__);
        $this->$reader_method($id);
    }

    protected function read_content_type(int $id) {
        return \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT ctype FROM media__content WHERE id=:P", [":P" => $id]), ['Strip', 'Trim', 'NEString', 'DefaultNull']);
    }

    protected function API_get_ctVIDEO(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctVIDEO\MediaContentObject::F($id));
    }

    protected function API_get_ctSEASON(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctSEASON\MediaContentObject::F($id));
    }

    protected function API_get_ctSEASONSEASON(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctSEASONSEASON\MediaContentObject::F($id));
    }

    protected function API_get_ctSEASONSERIES(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctSEASONSERIES\MediaContentObject::F($id));
    }

    protected function API_get_ctBANNER(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctBANNER\MediaContentObject::F($id));
        $this->API_language_list();
    }

    protected function API_get_ctCOLLECTION(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctCOLLECTION\MediaContentObject::F($id));
        $this->API_language_list();
    }

    protected function API_get_ctGIF(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctGIF\MediaContentObject::F($id));
        $this->API_language_list();
    }

    protected function API_get_ctTEXT(int $id) {
        $this->out->add('data', \Content\MediaContent\Readers\ctTEXT\MediaContentObject::F($id));
        $this->API_language_list();
    }

    protected function API_list() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_PREVIEW)) {
            \ImageFly\MediaContextInfo::register_media_context(\Content\MediaContent\Readers\ctSEASON\MediaContentObject::MEDIA_CONTEXT_PREVIEW, 1600, 1600, 100, 100);
        }
        $this->out->add("metadata", \Content\MediaContent\MediaContentTypeList::F());
        \Content\MediaContent\lister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_put() {
        $raw_data = \DataMap\InputDataMap::F()->get_filtered("data", ["Trim", "NEString", "JSONString", "DefaultNull"]);
        $raw_data ? 0 : \Errors\common_error::R("invalid request");
        $raw_map = \DataMap\CommonDataMap::F()->rebind($raw_data);
        $content_type = $raw_map->get_filtered("content_type", ["Strip", "Trim", "NEString", "DefaultNull"]);
        $content_type ? 0 : \Errors\common_error::R("invalid request");
        $method = "API_put_{$content_type}";
        method_exists($this, $method) ? 0 : \Errors\common_error::RF("no appropriate writer method for content-type `%s` in %s", $content_type, __METHOD__);
        $this->$method($raw_map);
    }

    protected function API_put_ctVIDEO(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctVIDEO\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctSEASON(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctSEASON\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctSEASONSEASON(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctSEASONSEASON\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctSEASONSERIES(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctSEASONSERIES\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctBANNER(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctBANNER\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctCOLLECTION(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctCOLLECTION\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctGIF(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctGIF\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_put_ctTEXT(\DataMap\IDataMap $raw_map) {
        $writer = \Content\MediaContent\Writers\ctTEXT\Writer::F($raw_map);
        $writer->run();
        $this->API_get($writer->result_id);
    }

    protected function API_language_list() {
        $this->out->add('metadata', [
            'language_list' => \Language\LanguageList::F(),
        ]);
    }

    protected function API_get_trailer(int $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid_request");
        $this->out->add('data', \Content\MediaContent\Readers\Trailer\MediaContentObject::F($id));
        $this->API_language_list();
    }

    protected function API_put_trailer() {
        $raw_data = \DataMap\InputDataMap::F()->get_filtered("data", ["Trim", "NEString", "JSONString", "DefaultNull"]);
        $raw_data ? 0 : \Errors\common_error::R("invalid request");
        $raw_map = \DataMap\CommonDataMap::F()->rebind($raw_data);
        $writer = \Content\MediaContent\Writers\Trailer\Writer::F($raw_map);
        $writer->run();
        $this->API_get_trailer($writer->result_id);
    }

    protected function API_list_trailers() {

        \Content\MediaContent\Listers\TrailerList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_remove_trailer() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            try {
                \Content\MediaContent\Removers\TrailerRemover::F($id_to_remove)->run();
            } catch (\Throwable $e) {
                $this->out->add('ermove_error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTraceAsString()
                ]);
            }
        }
        $this->API_list_trailers();
    }

    protected function API_remove_season_series() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            try {
                \Content\MediaContent\Removers\SeasonSerieRemover::F($id_to_remove)->run();
            } catch (\Throwable $e) {
                $this->out->add('ermove_error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTraceAsString()
                ]);
            }
        }
        $this->API_list_season_series();
    }

    protected function API_remove_season_season() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            try {
                \Content\MediaContent\Removers\SeasonSeasonRemover::F($id_to_remove)->run();
            } catch (\Throwable $e) {
                $this->out->add('ermove_error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTraceAsString()
                ]);
            }
        }
        $this->API_list_season_seasons();
    }

    protected function API_remove_content() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            try {
                $content_type = $this->read_content_type($id_to_remove);
                if ($content_type) {
                    $method = "APIX_remove_{$content_type}";
                    if (method_exists($this, $method)) {
                        $this->$method($id_to_remove);
                    }
                }
            } catch (\Throwable $e) {
                $this->out->add('ermove_error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTraceAsString()
                ]);
            }
        }
        $this->API_list();
    }

    protected function APIX_remove_ctVIDEO(int $id) {
        \Content\MediaContent\Removers\VideoRemover::F($id)->run();
    }

    protected function APIX_remove_ctSEASON(int $id) {
        \Content\MediaContent\Removers\SeasonRemover::F($id)->run();
    }

    protected function APIX_remove_ctCOLLECTION(int $id) {
        \Content\MediaContent\Removers\CollectionRemover::F($id)->run();
    }

    protected function APIX_remove_ctGIF(int $id) {
        \Content\MediaContent\Removers\GIFRemover::F($id)->run();
    }

    protected function APIX_remove_ctTEXT(int $id) {
        \Content\MediaContent\Removers\TEXTRemover::F($id)->run();
    }

    protected function API_list_season_seasons() {
        \Content\MediaContent\Listers\SeasonSeasonsLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_season_series() {
        \Content\MediaContent\Listers\SeasonSeriesLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_ribbon_list() {
        $language = \Language\LanguageList::F()->get_current_language();
        $offset = \DataMap\InputDataMap::F()->get_filtered('ribbon_offset', ['IntMore0', 'Default0']);
        $perpage = 100;
        $ribbon = \Content\MediaContentRibbon\MediaContentRibbon::F($language, $offset, $perpage, true);
        $this->out->add('ribbon', $ribbon);
    }

    protected function API_ribbon_remove() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            \Content\MediaContentRibbon\MediaContentRibbon::delete($id_to_remove);
        }
        $this->API_ribbon_list();
    }

    protected function API_lent_add_content() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['NEArray', 'ArrayOfInt', 'NEArray', 'DefaultNull']);
        if ($id) {
            \Content\MediaContentRibbon\MediaContentRibbon::prepend_array($id);
        }
        $this->API_ribbon_list();
    }

    protected function API_list_content_trailers() {
        \Content\MediaContent\Listers\TrailerList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_soap_trailers() {
        \Content\MediaContent\Listers\SoapTrailerList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_soap_series() {
        \Content\MediaContent\Listers\SoapSeriesList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_soap_seasons() {
        \Content\MediaContent\Listers\SoapSeasonsList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_list_banners() {
        \Content\MediaContent\Listers\BannerList::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_remove_banner() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            try {
                \Content\MediaContent\Removers\BannerRemover::F($id_to_remove)->run();
            } catch (\Throwable $e) {
                $this->out->add('ermove_error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'backtrace' => $e->getTraceAsString()
                ]);
            }
        }
        $this->API_list_banners();
    }

    protected function API_list_soap_and_videos() {
        \Content\MediaContent\CollectionItemLister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

}
