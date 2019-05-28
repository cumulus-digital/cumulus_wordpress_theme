/**
 * Custom Gutenberg Block for FORCE flipcards
 */
( function( blocks, element ) {
    const { Button, Panel, PanelBody, PanelRow, ToggleControl } = wp.components;
    const { RichText, MediaUpload, PlainText } = wp.editor;
    const { InspectorControls, ColorPalette } = wp.editor;

    const el = element.createElement;
 
    const cards = {
        FOCUSED: {
            bg: 'focusedBackground',
            content: (
                <p>
                    We will make every decision, including where we direct our own work efforts,
                    through the lens of <strong>"HABU"</strong> (Is this the "highest and best use" of our
                    resources – our people, our time, our energies and our money?) and will
                    ensure that we have a thoughtful plan to execute each decision and activity.
                </p>
            )
        },
        RESPONSIBLE: {
            bg: 'responsibleBackground',
            content: (
                <p>
                    We will not mistake activity for accomplishment. We will operate as a transparent and
                    performance-based company, with all of us taking responsibility for our efforts and
                    outcomes, celebrating our successes and their shepherds, and owning up to — and
                    learning from — our failures.
                </p>
            )
        },
        COLLABORATIVE: {
            bg: 'collaborativeBackground',
            content: (
                <p>
                    We will work across departments and disciplines to proactively support each other's
                    efforts and endeavors. Silos will be replaced by community; secrets and unresponsiveness
                    supplanted by constructive communication and responsiveness to each other’s needs. We
                    will work as a team with shared goals and successes.
                </p>
            )
        },
        EMPOWERED: {
            bg: 'empoweredBackground',
            content: (
                <p>
                    We will be empowered as individuals, valued for, and supported in the unique contributions
                    we each can make. Without exception, we will contribute our talents and time to meeting
                    challenges, fixing problems and rising to the opportunities before us. We will become more
                    empowered individually, and therefore more powerful as a whole.
                </p>
            )
        },
    };

    const getImageButton = ( openEvent, attribute, label ) => {
        let ret;
        if ( attribute ) {
            ret = (
                <div class="flipcard-image">
                    <img
                        src={ attribute }
                        className="flipcard-${ attrib }-bgimage"
                        alt="vFlipCard Background"
                        style={ {
                            width: "50%",
                        } }
                    />
                    <Button
                        onClick={ openEvent }
                        className="button button-large"
                    >
                        Change { label }
                    </Button>
                </div>
            );
        } else {
            ret = (
                <div className="button-container">
                    <Button
                        onClick={ openEvent }
                        className="button button-large"
                    >
                        Choose { label }
                    </Button>
                </div>
            );
        }
        return ret;
    };

    function makeFaces(cards, attributes) {
        let faces = [];
        for (let label in cards) {
            let card = cards[label];
            let bg = card.bg;
            let prop = attributes[bg];
            faces.push(
                <div className="flipcard">
                    <div className="flipcard-content">
                        <div
                            className="flipcard-face"
                            style={ {
                                backgroundImage: prop ? `url(${ prop }` : `none`,
                            } }
                        >
                            { attributes.useColorWash &&
                                <div class="flipcard-colorwash"></div>
                            }
                            <h1>
                                We are { label }.
                            </h1>
                        </div>
                        <div className="flipcard-face">
                            <h2>We are { label }.</h2>
                            { card.content }
                        </div>
                    </div>
                </div>
            );
        }
        return faces;
    };

    function makeControls(cards, attributes, setAttributes) {
        let bgControls = [];
        for (let label in cards) {
            let card = cards[label];
            let bg = card.bg;
            let prop = attributes[bg];
            bgControls.push(
                <PanelBody title={ `${ label } Background` } >
                    <PanelRow>
                        <MediaUpload
                            id={ `flipcard-${ label }-bgimage` }
                            label={ `${ label } Background Image` }
                            onSelect={
                                media => {
                                    setAttributes( { [bg]: media.sizes.full.url } );
                                }
                            }
                            type="image"
                            value={ prop }
                            render={ ( { open } ) => getImageButton( open, prop, 'Background Image' ) }
                        />
                    </PanelRow>
                </PanelBody>
            );
        }
        return bgControls;
    }

    blocks.registerBlockType( 'cumulus-gutenberg/force-values', {
        title: 'FORCE Values',
        icon: {
        	src: 'groups',
        	foreground: '#3399cc'
        },
        category: 'common',
        attributes: {
            useColorWash: {
                attribute: 'boolean',
                default: false,
            },
            focusedBackground: {
                attribute: 'string',
                default: null,
            },
            responsibleBackground: {
                attribute: 'string',
                default: null,
            },
            collaborativeBackground: {
                attribute: 'string',
                default: null,
            },
            empoweredBackground: {
                attribute: 'string',
                default: null,
            },
        },
        edit( { attributes, className, setAttributes } ) {
            let faces = makeFaces(cards, attributes);
            let bgControls = makeControls(cards, attributes, setAttributes);

            return (
                <div className={ `flipcards ${ attributes.useColorWash ? 'flipcards-colorwash' : '' }` }>
                    { faces }
                    <InspectorControls>
                        <PanelBody title="Flipcard Options">
                            <PanelRow>
                                <ToggleControl
                                    label="Color Wash Backgrounds"
                                    checked={ attributes.useColorWash }
                                    onChange={
                                        checked => {
                                            setAttributes( { useColorWash: checked } );
                                        }
                                    }
                                />
                            </PanelRow>
                        </PanelBody>
                        { bgControls }
                    </InspectorControls>
                </div>
            );
        },
        save( { attributes } ) {
            let faces = makeFaces(cards, attributes);
            return (
                <div className={ `flipcards ${ attributes.useColorWash ? 'flipcards-colorwash' : '' }` }>
                    { faces }
                </div>
            );
        }
    } );
}(
    window.wp.blocks,
    window.wp.element
) );