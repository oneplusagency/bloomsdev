<?php

class Url_model extends DB\SQL\Mapper
{
    /**
     * @param DB\SQL $db
     */
    public function __construct( DB\SQL $db )
    {
        parent::__construct( $db, 'links' );
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $this->load();
        return $this->query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById( $id )
    {
        $this->load( array( 'id=?', $id ) );
        return $this->query;
    }

    /**
     * @param $hash
     * @return mixed
     */
    public function getByHash( $hash )
    {
        $this->load( array( 'hash=?', $hash ) );
        return $this->query;
    }

    /**
     * @param $hash
     * @param $url
     */
    public function add( $hash, $url )
    {

        $this->hash = $hash;
        $this->url  = $url;
        $this->save();
    }

    /**
     * @param $id
     */
    public function edit( $id )
    {
        $this->load( array( 'id=?', $id ) );
        $this->copyFrom( 'POST', function ( $val ) {

            return array_intersect_key( $val, array_flip( array( 'hash', 'url' ) ) );
        } );
        $this->update();
    }

    /**
     * @param $id
     */
    public function delete( $id )
    {
        $this->load( array( 'id=?', $id ) );
        $this->erase();
    }
}
