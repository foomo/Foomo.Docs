<?php
/*
 *  $Id: Search.php 251 2007-11-18 14:06:59Z jepso $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://sourceforge.net/projects/sensei>.
 */

/**
 * Sensei_Doc_Search
 *
 * @package     Sensei_Search
 * @category    Documentation
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
 * @link        http://sourceforge.net/projects/sensei
 * @author      Janne Vanhala <jpvanhal@cc.hut.fi>
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @version     $Revision: 251 $
 * @since       1.0
 */
class Sensei_Doc_Search extends Doctrine_Search
{
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->_options['generateRelations'] = false;
        
        if ( ! isset($this->_options['resource'])) {
            $table = new Doctrine_Table('Sensei_Doc', Doctrine_Manager::connection());
            $table->setColumn('section_index', 'string', 20, 'primary');
            $this->_options['resource'] = $table;
        }
        
        if ( ! isset($options['className'])) {
            $this->_options['className'] = 'Sensei_Doc_Index';
        }
        
        if ( ! isset($this->_options['toc'])) {
            $msg = 'Option "toc" is not set.';
            throw new Doctrine_Search_Exception($msg);
        }
        
        if (! $this->_options['toc'] instanceof Sensei_Doc_Toc) {
            $msg = 'Option "toc" is not an instance of Sensei_Doc_Toc.';
            throw new Doctrine_Search_Exception($msg);
        }
        
        if (empty($this->_options['fields'])) {
            $this->_options['fields'] = array('title', 'content');
        }
        
        $this->buildDefinition();
    }
    
    public function index()
    {
        $toc = $this->getOption('toc');

        for ($i = 0; $i < count($toc); $i++) {
            $this->indexSection($toc->getChild($i));
        }        
    }
    
    public function indexSection(Sensei_Doc_Section $section)
    {
        $fields = array(
            'title' => $section->getName(),
            'content' => $section->getText(),
            'section_index' => $section->getIndex()
        );
        
        $this->updateIndex($fields);
        
        for ($i = 0; $i < count($section); $i++) {
            $this->indexSection($section->getChild($i));
        }    
    }
}
