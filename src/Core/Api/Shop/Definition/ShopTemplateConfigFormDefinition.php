<?php declare(strict_types=1);

namespace Shopware\Api\Shop\Definition;

use Shopware\Api\Entity\EntityDefinition;
use Shopware\Api\Entity\EntityExtensionInterface;
use Shopware\Api\Entity\Field\DateField;
use Shopware\Api\Entity\Field\FkField;
use Shopware\Api\Entity\Field\IdField;
use Shopware\Api\Entity\Field\LongTextField;
use Shopware\Api\Entity\Field\ManyToOneAssociationField;
use Shopware\Api\Entity\Field\OneToManyAssociationField;
use Shopware\Api\Entity\Field\ReferenceVersionField;
use Shopware\Api\Entity\Field\StringField;
use Shopware\Api\Entity\Field\TenantIdField;
use Shopware\Api\Entity\Field\VersionField;
use Shopware\Api\Entity\FieldCollection;
use Shopware\Api\Entity\Write\Flag\CascadeDelete;
use Shopware\Api\Entity\Write\Flag\PrimaryKey;
use Shopware\Api\Entity\Write\Flag\Required;
use Shopware\Api\Entity\Write\Flag\SearchRanking;
use Shopware\Api\Shop\Collection\ShopTemplateConfigFormBasicCollection;
use Shopware\Api\Shop\Collection\ShopTemplateConfigFormDetailCollection;
use Shopware\Api\Shop\Event\ShopTemplateConfigForm\ShopTemplateConfigFormDeletedEvent;
use Shopware\Api\Shop\Event\ShopTemplateConfigForm\ShopTemplateConfigFormWrittenEvent;
use Shopware\Api\Shop\Repository\ShopTemplateConfigFormRepository;
use Shopware\Api\Shop\Struct\ShopTemplateConfigFormBasicStruct;
use Shopware\Api\Shop\Struct\ShopTemplateConfigFormDetailStruct;

class ShopTemplateConfigFormDefinition extends EntityDefinition
{
    /**
     * @var FieldCollection
     */
    protected static $primaryKeys;

    /**
     * @var FieldCollection
     */
    protected static $fields;

    /**
     * @var EntityExtensionInterface[]
     */
    protected static $extensions = [];

    public static function getEntityName(): string
    {
        return 'shop_template_config_form';
    }

    public static function getFields(): FieldCollection
    {
        if (self::$fields) {
            return self::$fields;
        }

        self::$fields = new FieldCollection([
            new TenantIdField(),
            (new IdField('id', 'id'))->setFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            new FkField('parent_id', 'parentId', self::class),
            new ReferenceVersionField(self::class, 'parent_version_id'),
            (new FkField('shop_template_id', 'shopTemplateId', ShopTemplateDefinition::class))->setFlags(new Required()),
            (new ReferenceVersionField(ShopTemplateDefinition::class))->setFlags(new Required()),
            (new StringField('type', 'type'))->setFlags(new Required()),
            (new StringField('name', 'name'))->setFlags(new Required(), new SearchRanking(self::HIGH_SEARCH_RANKING)),
            (new StringField('title', 'title'))->setFlags(new SearchRanking(self::HIGH_SEARCH_RANKING)),
            new LongTextField('options', 'options'),
            new DateField('created_at', 'createdAt'),
            new DateField('updated_at', 'updatedAt'),
            new ManyToOneAssociationField('parent', 'parent_id', self::class, false),
            new ManyToOneAssociationField('shopTemplate', 'shop_template_id', ShopTemplateDefinition::class, false),
            (new OneToManyAssociationField('children', self::class, 'parent_id', false, 'id'))->setFlags(new CascadeDelete()),
            (new OneToManyAssociationField('fields', ShopTemplateConfigFormFieldDefinition::class, 'shop_template_config_form_id', false, 'id'))->setFlags(new CascadeDelete(), new SearchRanking(self::ASSOCIATION_SEARCH_RANKING)),
        ]);

        foreach (self::$extensions as $extension) {
            $extension->extendFields(self::$fields);
        }

        return self::$fields;
    }

    public static function getRepositoryClass(): string
    {
        return ShopTemplateConfigFormRepository::class;
    }

    public static function getBasicCollectionClass(): string
    {
        return ShopTemplateConfigFormBasicCollection::class;
    }

    public static function getDeletedEventClass(): string
    {
        return ShopTemplateConfigFormDeletedEvent::class;
    }

    public static function getWrittenEventClass(): string
    {
        return ShopTemplateConfigFormWrittenEvent::class;
    }

    public static function getBasicStructClass(): string
    {
        return ShopTemplateConfigFormBasicStruct::class;
    }

    public static function getTranslationDefinitionClass(): ?string
    {
        return null;
    }

    public static function getDetailStructClass(): string
    {
        return ShopTemplateConfigFormDetailStruct::class;
    }

    public static function getDetailCollectionClass(): string
    {
        return ShopTemplateConfigFormDetailCollection::class;
    }
}