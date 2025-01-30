<?php

class User extends DB\SQL\Mapper
{
    /**
     * @param DB\SQL $db
     */
    public function __construct( DB\SQL $db )
    {
        parent::__construct( $db, 'user' );
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
     * @param $name
     */
    public function getByName( $name )
    {
        $this->load( array( 'username=?', $name ) );
    }

    public function add()
    {
        $this->copyFrom( 'POST', function ( $val ) {

            return array_intersect_key( $val, array_flip( array( 'name', 'age' ) ) );
        } );
        $this->save();
    }

    /**
     * @param $id
     */
    public function edit( $id )
    {
        $this->load( array( 'id=?', $id ) );
        $this->copyFrom( 'POST', function ( $val ) {

            return array_intersect_key( $val, array_flip( array( 'name', 'age' ) ) );
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
