<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/

/**
 * Verwerkt import van Free Places List uit Plato
 */
class SIW_Plato_Import_FPL extends SIW_Plato_Import {

    /**
     * Endpoint voor import
     *
     * @var string
     */
    protected $endpoint = 'GetAllFreePlaces';

    /**
     * Identifier van import
     *
     * @var string
     */
    protected $name = 'importeren FPL';

    /**
     * Name van background process
     *
     * @var string
     */
    protected $process_name = 'update_free_places';

    /**
     * Eigenschappen per project
     *
     * @var array
     */
    protected $properties = array(
        'project_id',
        'code',
        'free_m',
        'free_f',
        'no_more_from',
    );

    /**
     * Verwerk xml van Plato
     *
     * @return void
     */
    protected function process_xml() {

        $this->data = array();
        foreach ( $this->xml->project as $project ) {
            $project_data = array();
            foreach ( $this->properties as $property ) {
                $project_data[ $property ] = (string) $project->$property;
            }
            $this->data[] = $project_data;		
        }
    }

}
