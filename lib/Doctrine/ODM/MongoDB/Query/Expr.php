<?php
/*
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
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ODM\MongoDB\Query;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Query expression builder for ODM.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @since       1.0
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Expr extends \Doctrine\MongoDB\Query\Expr
{
    /**
     * The DocumentManager instance for this query
     *
     * @var DocumentManager
     */
    private $dm;

    /**
     * The ClassMetadata instance for the document being queried
     *
     * @var ClassMetadata
     */
    private $class;

    public function __construct(DocumentManager $dm, $cmd)
    {
        $this->dm = $dm;
        $this->cmd = $cmd;
    }

    public function setClassMetadata(ClassMetadata $class)
    {
        $this->class = $class;
    }

    /**
     * Checks that the value of the current field is a reference to the supplied document.
     */
    public function references($document)
    {
        if ($this->currentField) {
            $this->query[$this->currentField] = $this->class
                ? $this->dm->createDBRef($document, $this->class->getFieldMapping($this->currentField))
                : $this->dm->createDBRef($document);
        } else {
            $this->query = $this->dm->createDBRef($document);
        }

        return $this;
    }

    /**
     * Checks that the current field includes a reference to the supplied document.
     */
    public function includesReferenceTo($document)
    {
        if ($this->currentField) {
            $this->query[$this->currentField][$this->cmd . 'elemMatch'] = $this->class
                ? $this->dm->createDBRef($document, $this->class->getFieldMapping($this->currentField))
                : $this->dm->createDBRef($document);
        } else {
            $this->query[$this->cmd . 'elemMatch'] = $this->dm->createDBRef($document);
        }

        return $this;
    }
}